<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::firstOrCreate(
            ['name' => 'Basic'],
            [
                'country' => 'all',
                'business_profile' => 'all',
                'price' => 0.00,
                'currency' => 'usd',
                'billing_type' => 'lifetime',
                'duration_value' => null,
                'duration_unit' => 'unlimited',
                'trial_days' => 0,
                'memories_limit' => 1,
                'events_limit' => 1,
                'max_photos' => 5,
                'max_videos' => 0,
                'max_audios' => 0,
                'candles_limit' => 0,
                'flowers_limit' => 0,
                'max_comments' => 50,
                'has_ai_features' => false,
                'has_donations' => false,
                'is_active' => true,
            ]
        );

        Package::firstOrCreate(
            ['name' => 'Premium'],
            [
                'country' => 'all',
                'business_profile' => 'all',
                'price' => 79.95,
                'currency' => 'usd',
                'billing_type' => 'yearly',
                'duration_value' => 1,
                'duration_unit' => 'years',
                'trial_days' => 14,
                'memories_limit' => 5,
                'events_limit' => 5,
                'max_photos' => -1,
                'max_videos' => 10,
                'max_audios' => 20,
                'candles_limit' => 100,
                'flowers_limit' => 100,
                'max_comments' => 500,
                'has_ai_features' => true,
                'has_donations' => true,
                'is_active' => true,
            ]
        );

        Package::firstOrCreate(
            ['name' => 'Lifetime'],
            [
                'country' => 'all',
                'business_profile' => 'all',
                'price' => 159.95,
                'currency' => 'usd',
                'billing_type' => 'lifetime',
                'duration_value' => null,
                'duration_unit' => 'unlimited',
                'trial_days' => 14,
                'memories_limit' => -1,
                'events_limit' => -1,
                'max_photos' => -1,
                'max_videos' => -1,
                'max_audios' => -1,
                'candles_limit' => -1,
                'flowers_limit' => -1,
                'max_comments' => -1,
                'has_ai_features' => true,
                'has_donations' => true,
                'is_active' => true,
            ]
        );
    }
}

