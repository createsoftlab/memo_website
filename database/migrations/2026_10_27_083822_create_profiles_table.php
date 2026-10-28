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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('memory_id')->constrained()->cascadeOnDelete();
            // Personal details
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->enum('memorial_status', ['passed_away','living_memory']);
            $table->string('death_reason')->nullable();

            $table->date('date_of_birth')->nullable();
            $table->string('birth_location')->nullable();

            $table->date('date_of_death')->nullable();
            $table->string('death_location')->nullable();

            // Address details
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('living_address')->nullable();
            $table->string('zip_code')->nullable();

            // Privacy
            $table->enum('privacy', ['public','private'])->default('private');

            $table->string('access_username')->nullable();
            $table->string('access_password')->nullable();

            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
