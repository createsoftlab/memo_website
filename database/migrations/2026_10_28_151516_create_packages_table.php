<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            
            // Basic Package Information
            $table->string('name');
            $table->string('country', 10)->default('all')->index(); // 'all', 'lk', 'us', etc.
            $table->string('business_profile')->default('all');
            
            // Pricing & Multi-Currency Engine
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('usd')->index(); // 'usd', 'lkr', 'eur', 'gbp', 'aud'
            $table->enum('billing_type', ['monthly', 'yearly', 'lifetime'])->default('monthly');
            
            // Validity & Trial
            $table->integer('duration_value')->nullable();
            $table->enum('duration_unit', ['days', 'months', 'years', 'unlimited'])->default('days');
            $table->integer('trial_days')->default(0);

            // Memory Creator / Owner Quotas (-1 indicates Unlimited)
            $table->integer('events_limit')->default(1);
            $table->integer('max_photos')->default(20);
            $table->integer('max_videos')->default(2);
            $table->integer('max_audios')->default(5);
            $table->integer('candles_limit')->default(0);
            $table->integer('flowers_limit')->default(0);
            $table->integer('max_comments')->default(100);

            // Boolean Feature Toggles (Checkboxes)
            $table->boolean('has_ai_features')->default(false);
            $table->boolean('has_donations')->default(false);

            // General Public / Guest User Interaction Limits (-1 = Unlimited, 0 = Disabled)
            $table->integer('guest_story_limit')->default(5);
            $table->integer('guest_photo_limit')->default(10);
            $table->integer('guest_video_limit')->default(1);

            // Stripe Identifiers & Status
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
