<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memory;
use App\Models\Event;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class DonationController extends Controller
{
    // Donation Form එක පෙන්වීම (Memory හෝ Event එකට අදාළව)
    public function showForm($slug, Request $request)
    {
        $memoryQuery = Memory::with('profile');

        if (Schema::hasColumn('memories', 'slug')) {
            $memoryQuery->where('slug', $slug)
                ->orWhereHas('profile', function ($q) use ($slug) {
                    $q->where('slug', $slug);
                });
        } else {
            $memoryQuery->whereHas('profile', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        $memory = $memoryQuery->firstOrFail();

        // URL එකේ ?event_id= තියෙනවාදැයි බැලීම
        $event = null;
        if ($request->filled('event_id')) {
            $eventQuery = Event::query();
            if (Schema::hasColumn('events', 'memory_id')) {
                $eventQuery->where(function ($q) use ($memory) {
                    $q->where('memory_id', $memory->id);
                    if ($memory->user_id) {
                        $q->orWhere(function ($sub) use ($memory) {
                            $sub->whereNull('memory_id')->where('user_id', $memory->user_id);
                        });
                    }
                });
            } else {
                $eventQuery->where('user_id', $memory->user_id);
            }
            $event = $eventQuery->find($request->query('event_id'));
        }

        $userCountry = strtolower(Auth::user()?->country ?? 'all');
        $currency = ($userCountry === 'lk') ? 'LKR' : 'USD';

        return view('home.donation_form', compact('memory', 'event', 'currency'));
    }

    // Stripe Checkout වෙත යොමු කිරීම
    public function checkout(Request $request)
    {
        $request->validate([
            'memory_id'           => 'required|exists:memories,id',
            'event_id'            => 'nullable|exists:events,id',
            'donation_for'        => 'required|string|max:255',
            'donor_name'          => 'required|string|max:255',
            'donor_email'         => 'required|email|max:255',
            'donor_phone'         => 'required|string|max:50',
            'donation_amount'     => 'required|numeric|min:1',
            'currency'            => 'required|string|max:10',
            'referred_by'         => 'nullable|string|max:255',
            'donation_visibility' => 'required|in:public,private',
        ]);

        $amount = (float) $request->donation_amount;
        $currency = strtolower($request->currency);
        $returnUrl = url()->previous();

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'customer_email'       => $request->donor_email,
            'line_items' => [[
                'price_data' => [
                    'currency'     => $currency,
                    'product_data' => [
                        'name'        => 'Donation: ' . $request->donation_for,
                        'description' => 'Contributed by ' . $request->donor_name,
                    ],
                    'unit_amount' => (int) round($amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('donation.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => $returnUrl,
            'metadata'    => [
                'memory_id'           => (string) $request->memory_id,
                'event_id'            => (string) ($request->event_id ?? ''),
                'user_id'             => (string) (Auth::id() ?? 0),
                'donation_for'        => (string) $request->donation_for,
                'donor_name'          => (string) $request->donor_name,
                'donor_email'         => (string) $request->donor_email,
                'donor_phone'         => (string) $request->donor_phone,
                'amount'              => (string) $amount,
                'currency'            => (string) $currency,
                'referred_by'         => (string) ($request->referred_by ?? ''),
                'donation_visibility' => (string) $request->donation_visibility,
                'return_url'          => (string) $returnUrl,
            ],
        ]);

        return redirect()->away($session->url);
    }

    // ගෙවීමෙන් පසු Database එකට Save කිරීම
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

            $alreadySaved = DB::table('donations')->where('stripe_session_id', $sessionId)->exists();

            if (!$alreadySaved) {
                $userId = (int) ($meta->user_id ?? 0);
                $eventId = !empty($meta->event_id) ? (int) $meta->event_id : null;

                DB::table('donations')->insert([
                    'memory_id'         => (int) $meta->memory_id,
                    'event_id'          => $eventId, // Event එකක් තිබුණොත් event_id එක, නැත්නම් null
                    'user_id'           => $userId > 0 ? $userId : null,
                    'donation_for'      => (string) $meta->donation_for,
                    'donor_name'        => (string) $meta->donor_name,
                    'donor_email'       => (string) $meta->donor_email,
                    'donor_phone'       => !empty($meta->donor_phone) ? (string) $meta->donor_phone : null,
                    'amount'            => (float) $meta->amount,
                    'currency'          => (string) $meta->currency,
                    'referred_by'       => !empty($meta->referred_by) ? (string) $meta->referred_by : null,
                    'visibility'        => (string) $meta->donation_visibility,
                    'status'            => 'completed',
                    'stripe_session_id' => $sessionId,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }

            $memory = Memory::find((int) $meta->memory_id);
            $slug = $memory->slug ?? $memory->profile?->slug ?? null;

            if ($slug) {
                // Event එකකට donate කළා නම් Events page එකටද, Memory එකට නම් About page එකටද යවයි
                $redirectUrl = !empty($meta->event_id) ? '/memories_event/' . $slug : '/memory_index/' . $slug;
                return redirect($redirectUrl)->with('success', 'Thank you! Your donation has been recorded successfully.');
            }

            return redirect('/')->with('success', 'Donation successful.');
        } catch (\Throwable $e) {
            return redirect('/')->with('error', 'Error recording donation: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->back()->with('error', 'Donation payment was cancelled.');
    }
}
