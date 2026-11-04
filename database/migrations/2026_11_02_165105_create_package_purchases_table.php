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
        Schema::create('package_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();

            // Payment details
            $table->decimal('amount_paid', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('payment_method')->nullable(); // stripe, paypal, free, etc.
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('active'); // pending, active, cancelled, expired

            // Plan & Validity dates
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Lifetime නම් null විය හැක
            $table->timestamp('trial_ends_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_purchases');
    }
};
