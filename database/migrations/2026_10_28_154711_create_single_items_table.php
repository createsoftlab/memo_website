<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('single_items', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['candle', 'flower'])->index();
            $table->string('name');
            $table->string('country', 10)->default('all')->index();
            
            // Pricing & Currency
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('usd')->index();
            
            // Display Lifespan & Renewal Rates
            $table->integer('initial_duration_days')->default(7);
            $table->decimal('extend_price', 10, 2)->default(0.00);
            $table->integer('extend_duration_days')->default(30);

            // Stripe Identifiers
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('single_items');
    }
};
