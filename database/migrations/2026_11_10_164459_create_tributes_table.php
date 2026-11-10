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
        Schema::create('tributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Tribute එක මිලදී ගන්නා යූසර් (Donor)
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete(); // Single item package (Flower/Candle)

            $table->string('tribute_type'); // 'flower' or 'candle'
            $table->integer('flower_count')->default(0);
            $table->integer('candle_count')->default(0);

            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2);

            $table->text('message')->nullable();

            $table->timestamp('expire_date')->nullable();
            $table->string('status')->default('pending'); // pending, active, expired

            $table->string('stripe_session_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tributes');
    }
};
