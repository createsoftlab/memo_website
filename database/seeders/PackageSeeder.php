<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $packages = [
            // 1. Basic Free Package (Global)
            [
                'name'               => 'Basic Memorial Plan',
                'country'            => 'all',
                'business_profile'   => 'all',
                'price'              => 0.00,
                'currency'           => 'usd',
                'billing_type'       => 'lifetime',
                'duration_value'     => null,
                'duration_unit'      => 'unlimited',
                'trial_days'         => 0,
                'memories_limit'     => 1,
                'events_limit'       => 1,
                'max_photos'         => 10,
                'max_videos'         => 1,
                'max_audios'         => 1,
                'candles_limit'      => 0,
                'flowers_limit'      => 0,
                'max_comments'       => 50,
                'has_ai_features'    => false,
                'has_donations'      => false,
                'guest_story_limit'  => 2,
                'guest_photo_limit'  => 5,
                'guest_video_limit'  => 0,
                'stripe_product_id'  => null,
                'stripe_price_id'    => null,
                'is_active'          => true,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],

            // 2. Premium Yearly Package (Global - Most Popular)
            [
                'name'               => 'Premium Memorial Plan',
                'country'            => 'all',
                'business_profile'   => 'all',
                'price'              => 79.95,
                'currency'           => 'usd',
                'billing_type'       => 'yearly',
                'duration_value'     => 1,
                'duration_unit'      => 'years',
                'trial_days'         => 14,
                'memories_limit'     => -1, // -1 means Unlimited
                'events_limit'       => -1,
                'max_photos'         => -1,
                'max_videos'         => -1,
                'max_audios'         => -1,
                'candles_limit'      => -1,
                'flowers_limit'      => -1,
                'max_comments'       => -1,
                'has_ai_features'    => true,
                'has_donations'      => true,
                'guest_story_limit'  => -1,
                'guest_photo_limit'  => -1,
                'guest_video_limit'  => -1,
                'stripe_product_id'  => null, // Can be updated later via Stripe API sync
                'stripe_price_id'    => null,
                'is_active'          => true,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],

            // 3. Standard Monthly Package (Sri Lanka Specific)
            [
                'name'               => 'Standard Plan (LK)',
                'country'            => 'lk', // Country code mapping via your JS logic
                'business_profile'   => 'individual',
                'price'              => 2500.00,
                'currency'           => 'lkr',
                'billing_type'       => 'monthly',
                'duration_value'     => 1,
                'duration_unit'      => 'months',
                'trial_days'         => 0,
                'memories_limit'     => 5,
                'events_limit'       => 5,
                'max_photos'         => 100,
                'max_videos'         => 10,
                'max_audios'         => 20,
                'candles_limit'      => 50,
                'flowers_limit'      => 50,
                'max_comments'       => 500,
                'has_ai_features'    => false,
                'has_donations'      => true,
                'guest_story_limit'  => 10,
                'guest_photo_limit'  => 20,
                'guest_video_limit'  => 2,
                'stripe_product_id'  => null,
                'stripe_price_id'    => null,
                'is_active'          => true,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
        ];

        DB::table('packages')->insert($packages);
    }
}
