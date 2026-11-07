<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackagePurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use DateTime;
use DateTimeZone;

class BillingController extends Controller
{
    // 1. Upgrade / Renew පිටුව පෙන්වීම
    public function index()
    {
        $user = Auth::user();
        $currentPackage = Package::find($user->package_id);
        $packages = Package::where('is_active', true)->get();

        return view('memory_user.billing', compact('user', 'currentPackage', 'packages'));
    }

    // 2. Stripe Checkout පිටුවට යැවීම
    public function checkout(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'action' => 'required|in:upgrade,renew'
        ]);

        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);
        $amountPaid = (float) $package->price;
        $packageCurrency = strtoupper($package->currency ?? 'USD');

        $chargeAmountInCents = 0;
        $chargeCurrency = 'usd';

        if ($packageCurrency === 'LKR') {
            $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/LKR');
            if ($response->successful()) {
                $usdRate = $response->json('rates')['USD'];
                $chargeAmountInCents = intval(round(($amountPaid * $usdRate) * 100));
            } else {
                return back()->with('error', 'Currency API error');
            }
        } else {
            $chargeAmountInCents = intval(round($amountPaid * 100));
            $chargeCurrency = strtolower($packageCurrency);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkout_session = StripeSession::create([
            'payment_method_types' => ['card'],
            'customer_email' => $user->email,
            'line_items' => [[
                'price_data' => [
                    'currency' => $chargeCurrency,
                    'product_data' => [
                        'name' => ucfirst($request->action) . ' Package: ' . $package->name,
                    ],
                    'unit_amount' => $chargeAmountInCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('billing.index') . '?status=cancelled',
            'metadata' => [
                'user_id' => $user->id,
                'package_id' => $package->id,
                'action' => $request->action,
                'amount_paid' => $amountPaid,
                'currency' => $packageCurrency,
            ],
        ]);

        return redirect()->away($checkout_session->url);
    }

    // 3. Payment එක සාර්ථක වුනාට පස්සේ Database එක Update කිරීම (PDF එකේ උපදෙස් අනුව)
    public function success(Request $request)
    {
        if (!$request->has('session_id')) {
            return redirect()->route('dashboard')->with('error', 'Invalid payment session.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = StripeSession::retrieve($request->session_id);

            if ($session->payment_status !== 'paid') {
                return redirect()->route('billing.index')->with('error', 'Payment was not completed.');
            }

            $existingPurchase = PackagePurchase::where('transaction_id', $session->id)->first();
            if ($existingPurchase) {
                return redirect()->route('dashboard')->with('success', 'Your package is already updated.');
            }

            $user = Auth::user();
            $metadata = $session->metadata;
            $package = Package::find($metadata->package_id);

            // -------------------------------------------------------------------------
            // Package expire.pdf හි උපදෙස් අනුව Expiry Date එක සෑදීම
            // -------------------------------------------------------------------------

            // 1. Stripe එකෙන් ලැබෙන ගෝලීය නියම වේලාව (UTC) ලබා ගැනීම[cite: 8]
            $stripeTime = $session->created;
            $purchaseDate = new DateTime('@' . $stripeTime);

            // 2. පරිශීලකයාගේ රටේ Timezone එක ලබා ගැනීම[cite: 8] (නැතිනම් ලංකාවේ වේලාව භාවිතා වේ)
            $userTimezone = $user->timezone ?? 'Asia/Colombo';

            // 3. Stripe වේලාව පරිශීලකයා ඉන්න රටේ නියම වේලාවට හැරවීම[cite: 8]
            $purchaseDate->setTimezone(new DateTimeZone($userTimezone));

            $formattedExpiryDate = null;

            if ($package->billing_type !== 'lifetime' && strtolower($package->duration_unit) !== 'unlimited') {
                $durationValue = (int) ($package->duration_value ?: 1);
                $durationUnit = strtolower($package->duration_unit ?? 'months');

                // 4. පරිශීලකයාගේ රටේ වේලාවෙන්ම Expiry Date එක සෑදීම[cite: 8]
                $expiryDate = clone $purchaseDate; //[cite: 9]

                // Renew එකක් නම් සහ දැනටමත් expiry date එකක් තිබේ නම්
                if ($metadata->action === 'renew' && $user->package_expires_at) {
                    $currentExpiry = new DateTime($user->package_expires_at, new DateTimeZone($userTimezone));
                    $now = clone $purchaseDate;

                    // Expiry එක අනාගතයේ එකක් නම් එතැන් සිට කාලය දික් වේ
                    if ($currentExpiry > $now) {
                        $expiryDate = clone $currentExpiry;
                    }
                }

                // කාල සීමාව එකතු කිරීම (+30 days ආදී වශයෙන්)[cite: 9]
                if ($durationUnit === 'years' || $package->billing_type === 'yearly') {
                    $expiryDate->modify("+$durationValue years");
                } elseif ($durationUnit === 'days') {
                    $expiryDate->modify("+$durationValue days");
                } else {
                    $expiryDate->modify("+30 days"); // මාසයක් සඳහා[cite: 9]
                }

                $formattedExpiryDate = $expiryDate->format('Y-m-d H:i:s'); // Database එකට සේව් කිරීම[cite: 9]
            }
            // -------------------------------------------------------------------------

            DB::beginTransaction();

            PackagePurchase::create([
                'user_id'        => $user->id,
                'package_id'     => $package->id,
                'amount_paid'    => $metadata->amount_paid,
                'currency'       => $metadata->currency,
                'payment_method' => 'stripe_checkout',
                'transaction_id' => $session->id,
                'status'         => 'active',
                'starts_at'      => $purchaseDate->format('Y-m-d H:i:s'),
                'expires_at'     => $formattedExpiryDate,
            ]);

            $user->package_id = $package->id;
            $user->package_expires_at = $formattedExpiryDate;
            $user->save();

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Package successfully ' . $metadata->action . 'ed!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dashboard')->with('error', 'Error applying package: ' . $e->getMessage());
        }
    }
}
