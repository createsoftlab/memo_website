<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_id')->constrained('memories')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('donation_for');
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('usd');
            $table->string('referred_by')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->string('status')->default('completed');
            $table->string('stripe_session_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
