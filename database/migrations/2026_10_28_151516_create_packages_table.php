<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->enum('billing_type', ['monthly', 'yearly', 'lifetime']);
            $table->string('country')->default('all');
            $table->string('business_profile')->default('all');
            $table->integer('duration_value')->nullable();
            $table->enum('duration_unit', ['days', 'months', 'years', 'unlimited'])->default('days');
            $table->integer('trial_days')->default(0);

            // Feature & limit attributes
            $table->integer('max_photos')->default(0);
            $table->integer('max_videos')->default(0);
            $table->integer('max_comments')->default(0);
            $table->integer('candles_limit')->default(0);
            $table->integer('flowers_limit')->default(0);

            // Stripe identifiers
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
