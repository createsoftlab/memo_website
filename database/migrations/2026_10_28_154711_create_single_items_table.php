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
        Schema::create('single_items', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['candle', 'flower']);
            $table->string('name');
            $table->string('country')->default('all');
            $table->decimal('price', 8, 2);
            $table->integer('initial_duration_days')->default(7);
            $table->decimal('extend_price', 8, 2)->default(0.00);
            $table->integer('extend_duration_days')->default(30);

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
        Schema::dropIfExists('single_items');
    }
};
