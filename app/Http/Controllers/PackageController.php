<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\SingleItem;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Exception;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret') ?? env('STRIPE_SECRET'));
    }

    public function index()
    {
        $packages = Package::latest()->get();
        $singleItems = SingleItem::latest()->get();

        return view('admin.view_pakages', compact('packages', 'singleItems'));
    }

    public function create()
    {
        return view('admin.create_package');
    }

    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'currency'          => 'required|string|in:usd,lkr,eur,gbp,aud',
            'billing_type'      => 'required|in:monthly,yearly,lifetime',
            'country'           => 'required|string|max:10',
            'business_profile'  => 'required|string|max:100',
            'duration_value'    => 'nullable|integer',
            'duration_unit'     => 'required|in:days,months,years,unlimited',
            'trial_days'        => 'nullable|integer|min:0',
            
            // Memory Creator Limits
            'events_limit'      => 'nullable|integer',
            'max_photos'        => 'nullable|integer',
            'max_videos'        => 'nullable|integer',
            'max_audios'        => 'nullable|integer',
            'candles_limit'     => 'nullable|integer|min:0',
            'flowers_limit'     => 'nullable|integer|min:0',
            'max_comments'      => 'nullable|integer',
            
            // Feature Toggles (Checkboxes)
            'has_ai_features'   => 'nullable|boolean',
            'has_donations'     => 'nullable|boolean',

            // General Public / Guest Limits
            'guest_story_limit' => 'nullable|integer',
            'guest_photo_limit' => 'nullable|integer',
            'guest_video_limit' => 'nullable|integer',
        ]);

        // Normalize checkboxes
        $validated['has_ai_features'] = $request->boolean('has_ai_features');
        $validated['has_donations']   = $request->boolean('has_donations');

        try {
            DB::beginTransaction();

            // 1. Create Product on Stripe
            $stripeProduct = $this->stripe->products->create([
                'name' => $validated['name'],
                'metadata' => [
                    'type'             => 'memorial_package',
                    'country'          => $validated['country'],
                    'has_ai'           => $validated['has_ai_features'] ? 'yes' : 'no',
                    'has_donations'    => $validated['has_donations'] ? 'yes' : 'no',
                ]
            ]);

            // 2. Create Pricing with Currency
            $currency = strtolower($validated['currency']);
            $unitAmount = (int) round($validated['price'] * 100);

            $pricePayload = [
                'unit_amount' => $unitAmount,
                'currency'    => $currency,
                'product'     => $stripeProduct->id,
            ];

            if ($validated['billing_type'] === 'monthly') {
                $pricePayload['recurring'] = ['interval' => 'month'];
            } elseif ($validated['billing_type'] === 'yearly') {
                $pricePayload['recurring'] = ['interval' => 'year'];
            }

            $stripePrice = $this->stripe->prices->create($pricePayload);

            // 3. Store into DB
            Package::create(array_merge($validated, [
                'stripe_product_id' => $stripeProduct->id,
                'stripe_price_id'   => $stripePrice->id,
            ]));

            DB::commit();
            return redirect()->route('admin.view_pakages')->with('success', 'Memorial Package published successfully with Stripe!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Stripe Integration Error: ' . $e->getMessage());
        }
    }

    public function updatePackage(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'currency'          => 'required|string|in:usd,lkr,eur,gbp,aud',
            'billing_type'      => 'required|in:monthly,yearly,lifetime',
            'country'           => 'required|string|max:10',
            'business_profile'  => 'required|string|max:100',
            'duration_value'    => 'nullable|integer',
            'duration_unit'     => 'required|in:days,months,years,unlimited',
            'trial_days'        => 'nullable|integer|min:0',
            'events_limit'      => 'nullable|integer',
            'max_photos'        => 'nullable|integer',
            'max_videos'        => 'nullable|integer',
            'max_audios'        => 'nullable|integer',
            'candles_limit'     => 'nullable|integer|min:0',
            'flowers_limit'     => 'nullable|integer|min:0',
            'max_comments'      => 'nullable|integer',
            'has_ai_features'   => 'nullable|boolean',
            'has_donations'     => 'nullable|boolean',
            'guest_story_limit' => 'nullable|integer',
            'guest_photo_limit' => 'nullable|integer',
            'guest_video_limit' => 'nullable|integer',
        ]);

        $validated['has_ai_features'] = $request->boolean('has_ai_features');
        $validated['has_donations']   = $request->boolean('has_donations');

        // Note: Stripe price objects cannot be edited directly; price changes update the local model
        $package->update($validated);

        return redirect()->back()->with('success', 'Package details updated successfully.');
    }

    public function destroyPackage(Package $package)
    {
        $package->delete();
        return redirect()->back()->with('success', 'Package removed successfully.');
    }

    public function storeItem(Request $request)
    {
        $validated = $request->validate([
            'type'                  => 'required|in:candle,flower',
            'name'                  => 'required|string|max:255',
            'country'               => 'required|string|max:10',
            'price'                 => 'required|numeric|min:0',
            'currency'              => 'required|string|in:usd,lkr,eur,gbp,aud',
            'initial_duration_days' => 'required|integer|min:1',
            'extend_price'          => 'required|numeric|min:0',
            'extend_duration_days'  => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $stripeProduct = $this->stripe->products->create([
                'name' => $validated['name'],
                'metadata' => [
                    'item_type' => $validated['type'],
                    'country'   => $validated['country'],
                ]
            ]);

            $stripePrice = $this->stripe->prices->create([
                'unit_amount' => (int) round($validated['price'] * 100),
                'currency'    => strtolower($validated['currency']),
                'product'     => $stripeProduct->id,
            ]);

            SingleItem::create(array_merge($validated, [
                'stripe_product_id' => $stripeProduct->id,
                'stripe_price_id'   => $stripePrice->id,
            ]));

            DB::commit();
            return redirect()->route('admin.view_pakages')->with('success', 'Single tribute item published successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Stripe Item Error: ' . $e->getMessage());
        }
    }

    public function updateItem(Request $request, SingleItem $item)
    {
        $validated = $request->validate([
            'type'                  => 'required|in:candle,flower',
            'name'                  => 'required|string|max:255',
            'country'               => 'required|string|max:10',
            'price'                 => 'required|numeric|min:0',
            'currency'              => 'required|string|in:usd,lkr,eur,gbp,aud',
            'initial_duration_days' => 'required|integer|min:1',
            'extend_price'          => 'required|numeric|min:0',
            'extend_duration_days'  => 'required|integer|min:1',
        ]);

        $item->update($validated);
        return redirect()->back()->with('success', 'Tribute item updated successfully.');
    }

    public function destroyItem(SingleItem $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Tribute item deleted successfully.');
    }
}
