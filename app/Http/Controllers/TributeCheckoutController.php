<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SingleItem;
use App\Models\Memory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Carbon\Carbon;

class TributeCheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'memory_id'    => 'required',
            'package_id'   => 'required',
            'tribute_type' => 'required|in:flower,candle',
            'quantity'     => 'required|integer|min:1',
            'donor_name'   => 'required|string|max:150',
            'message'      => 'nullable|string|max:1000',
        ]);

        $item = SingleItem::findOrFail($request->package_id);
        $quantity = (int) $request->quantity;

        Stripe::setApiKey(config('services.stripe.secret'));

        // Form එක Submit කළ මුල් පිටුවේ සම්පූර්ණ URL එක ලබා ගැනීම (උදා: /memory_index/robin-hood)
        $returnUrl = url()->previous();

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($item->currency ?? 'usd'),
                    'product_data' => [
                        'name' => $item->name . ' (' . ucfirst($request->tribute_type) . ')',
                        'description' => 'Tribute by: ' . $request->donor_name,
                    ],
                    'unit_amount' => (int) round($item->price * 100),
                ],
                'quantity' => $quantity,
            ]],
            'mode' => 'payment',
            'success_url' => route('tribute.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => $returnUrl,
            'metadata' => [
                'user_id'       => (string) (Auth::id() ?? 0),
                'donor_name'    => (string) $request->donor_name,
                'memory_id'     => (string) $request->memory_id,
                'package_id'    => (string) $item->id,
                'tribute_type'  => (string) $request->tribute_type,
                'quantity'      => (string) $quantity,
                'unit_price'    => (string) $item->price,
                'message'       => (string) ($request->message ?? ''),
                'duration_days' => (string) ($item->initial_duration_days ?? 7),
                'return_url'    => (string) $returnUrl, // සාර්ථක වූ පසු ආපසු යා යුතු URL එක
            ],
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect('/')->with('error', 'No session found.');
        }

        try {
            $session = StripeSession::retrieve($sessionId);

            if (!$session || $session->payment_status !== 'paid') {
                return redirect('/')->with('error', 'Payment was not completed.');
            }

            $meta = $session->metadata;

            // Session ID එක පරීක්ෂා කර Duplicate insert වීම වැළැක්වීම
            $alreadySaved = DB::table('tributes')->where('stripe_session_id', $sessionId)->exists();

            if (!$alreadySaved) {
                $days = !empty($meta->duration_days) ? (int) $meta->duration_days : 7;
                $expireDate = Carbon::now()->addDays($days);

                $qty = (int) ($meta->quantity ?? 1);
                $unitPrice = (float) ($meta->unit_price ?? 0);
                $userId = (int) ($meta->user_id ?? 0);

                DB::table('tributes')->insert([
                    'memory_id'         => (int) $meta->memory_id,
                    'user_id'           => $userId > 0 ? $userId : null,
                    'package_id'        => (int) $meta->package_id,
                    'tribute_type'      => (string) $meta->tribute_type,
                    'flower_count'      => $meta->tribute_type === 'flower' ? $qty : 0,
                    'candle_count'      => $meta->tribute_type === 'candle' ? $qty : 0,
                    'unit_price'        => $unitPrice,
                    'total_amount'      => (float) ($unitPrice * $qty),
                    'message'           => !empty($meta->message) ? $meta->message : $meta->donor_name,
                    'expire_date'       => $expireDate,
                    'status'            => 'active',
                    'stripe_session_id' => $sessionId,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }

            // 1. Metadata හි ඇති මුල් පිටුවේ URL එකට Redirect කිරීම
            if (!empty($meta->return_url)) {
                return redirect($meta->return_url)->with('success', 'Thank you! Your tribute has been placed successfully.');
            }

            // 2. Fallback: Slug එක මඟින් Redirect කිරීම
            $memory = Memory::find((int) $meta->memory_id);
            $slug = $memory->slug ?? $memory->memory_slug ?? null;
            if ($slug) {
                return redirect('/memory_index/' . $slug)->with('success', 'Thank you! Your tribute has been placed successfully.');
            }

            return redirect('/')->with('success', 'Tribute payment successful.');
        } catch (\Throwable $e) {
            return redirect('/')->with('error', 'Error recording tribute: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->back()->with('error', 'Tribute payment was cancelled.');
    }
}
