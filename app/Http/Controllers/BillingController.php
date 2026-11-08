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
    public function index()
    {
        $user = Auth::user();
        $currentPackage = Package::find($user->package_id);
        $packages = Package::where('is_active', true)->get();

        return view('memory_user.billing', compact('user', 'currentPackage', 'packages'));
    }

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

            $stripeTime = $session->created;
            $purchaseDate = new DateTime('@' . $stripeTime);
            $userTimezone = $user->timezone ?? 'Asia/Colombo';
            $purchaseDate->setTimezone(new DateTimeZone($userTimezone));

            $formattedExpiryDate = null;

            if ($package->billing_type !== 'lifetime' && strtolower($package->duration_unit) !== 'unlimited') {
                $durationValue = (int) ($package->duration_value ?: 1);
                $durationUnit = strtolower($package->duration_unit ?? 'months');

                $expiryDate = clone $purchaseDate;

                if ($metadata->action === 'renew' && $user->package_expires_at) {
                    $currentExpiry = new DateTime($user->package_expires_at, new DateTimeZone($userTimezone));
                    $now = clone $purchaseDate;
                    if ($currentExpiry > $now) {
                        $expiryDate = clone $currentExpiry;
                    }
                }

                if ($durationUnit === 'years' || $package->billing_type === 'yearly') {
                    $expiryDate->modify("+$durationValue years");
                } elseif ($durationUnit === 'days') {
                    $expiryDate->modify("+$durationValue days");
                } else {
                    $expiryDate->modify("+30 days");
                }

                $formattedExpiryDate = $expiryDate->format('Y-m-d H:i:s');
            }

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
