<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\SingleItem;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Exception;

class PackageController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function create()
    {
        return view('admin.create_package');
    }

    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'billing_type'     => 'required|in:monthly,yearly,lifetime',
            'country'          => 'required|string|max:50',
            'business_profile' => 'required|string|max:100',
            'duration_value'   => 'nullable|integer',
            'duration_unit'    => 'required|in:days,months,years,unlimited',
            'trial_days'       => 'nullable|integer|min:0',
            'max_photos'       => 'nullable|integer',
            'max_videos'       => 'nullable|integer',
            'max_comments'     => 'nullable|integer',
            'candles_limit'    => 'nullable|integer|min:0',
            'flowers_limit'    => 'nullable|integer|min:0',
        ]);

        try {
            // Stripe e Product create
            $stripeProduct = $this->stripe->products->create([
                'name' => $validated['name'],
                'metadata' => [
                    'type'             => 'package',
                    'business_profile' => $validated['business_profile'],
                    'country'          => $validated['country'],
                ]
            ]);

            // Price setup (recurring ba one-time)
            $priceData = [
                'unit_amount' => (int) round($validated['price'] * 100), // Stripe cent hishabe nay
                'currency'    => 'usd',
                'product'     => $stripeProduct->id,
            ];

            if ($validated['billing_type'] === 'monthly') {
                $priceData['recurring'] = ['interval' => 'month'];
            } elseif ($validated['billing_type'] === 'yearly') {
                $priceData['recurring'] = ['interval' => 'year'];
            }

            $stripePrice = $this->stripe->prices->create($priceData);

            // Database e save kora
            Package::create(array_merge($validated, [
                'trial_days'        => $validated['trial_days'] ?? 0,
                'max_photos'        => $validated['max_photos'] ?? 0,
                'max_videos'        => $validated['max_videos'] ?? 0,
                'max_comments'      => $validated['max_comments'] ?? 0,
                'candles_limit'     => $validated['candles_limit'] ?? 0,
                'flowers_limit'     => $validated['flowers_limit'] ?? 0,
                'stripe_product_id' => $stripeProduct->id,
                'stripe_price_id'   => $stripePrice->id,
            ]));

            return redirect()->back()->with('success', 'Package successfully created with Stripe integration!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Stripe Error: ' . $e->getMessage());
        }
    }

    // 2. Single Item (Flower/Candle) Save kora
    public function storeItem(Request $request)
    {
        $validated = $request->validate([
            'type'                  => 'required|in:candle,flower',
            'name'                  => 'required|string|max:255',
            'country'               => 'required|string|max:50',
            'price'                 => 'required|numeric|min:0',
            'initial_duration_days' => 'required|integer|min:1',
            'extend_price'          => 'required|numeric|min:0',
            'extend_duration_days'  => 'required|integer|min:1',
        ]);

        try {
            // Stripe product toiri
            $stripeProduct = $this->stripe->products->create([
                'name' => $validated['name'],
                'metadata' => [
                    'type'                  => $validated['type'],
                    'initial_duration_days' => $validated['initial_duration_days'],
                    'extend_price'          => $validated['extend_price'],
                    'extend_duration_days'  => $validated['extend_duration_days'],
                ]
            ]);

            // Base price toiri
            $stripePrice = $this->stripe->prices->create([
                'unit_amount' => (int) round($validated['price'] * 100),
                'currency'    => 'usd',
                'product'     => $stripeProduct->id,
            ]);

            // Database e item record create
            SingleItem::create(array_merge($validated, [
                'stripe_product_id' => $stripeProduct->id,
                'stripe_price_id'   => $stripePrice->id,
            ]));

            return redirect()->back()->with('success', 'Single item added successfully with Stripe!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Stripe Error: ' . $e->getMessage());
        }
    }

    // 1. Packages & Items view load karne ke liye method
    public function index()
    {
        $packages = Package::latest()->get();
        $singleItems = SingleItem::latest()->get();

        return view('admin.view_pakages', compact('packages', 'singleItems'));
    }

    // 2. Package Update
    public function updatePackage(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'billing_type'     => 'required|in:monthly,yearly,lifetime',
            'country'          => 'required|string|max:50',
            'business_profile' => 'required|string|max:100',
            'duration_value'   => 'nullable|integer',
            'duration_unit'    => 'required|in:days,months,years,unlimited',
            'trial_days'       => 'nullable|integer|min:0',
            'max_photos'       => 'nullable|integer',
            'max_videos'       => 'nullable|integer',
            'max_comments'     => 'nullable|integer',
            'candles_limit'    => 'nullable|integer|min:0',
            'flowers_limit'    => 'nullable|integer|min:0',
        ]);

        $package->update($validated);

        return redirect()->back()->with('success', 'Package safaltapoorvak update ho gaya!');
    }

    // 3. Package Delete
    public function destroyPackage(Package $package)
    {
        $package->delete();
        return redirect()->back()->with('success', 'Package delete ho gaya!');
    }

    // 4. Single Item Update
    public function updateItem(Request $request, SingleItem $item)
    {
        $validated = $request->validate([
            'type'                  => 'required|in:candle,flower',
            'name'                  => 'required|string|max:255',
            'country'               => 'required|string|max:50',
            'price'                 => 'required|numeric|min:0',
            'initial_duration_days' => 'required|integer|min:1',
            'extend_price'          => 'required|numeric|min:0',
            'extend_duration_days'  => 'required|integer|min:1',
        ]);

        $item->update($validated);

        return redirect()->back()->with('success', 'Single item safaltapoorvak update ho gaya!');
    }

    // 5. Single Item Delete
    public function destroyItem(SingleItem $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Single item delete ho gaya!');
    }
}
