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
        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('memorial_type', ['passed_away','living_memory']);
            $table->string('website_url')->nullable();
            // Publication status
            $table->enum('status', ['draft','published'])->default('draft');
            $table->text('story')->nullable();
            $table->text('funeral_location_name')->nullable();
            $table->text('funeral_location_address')->nullable();
            $table->text('family_contact_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memories');
    }
};
