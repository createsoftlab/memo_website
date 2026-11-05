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
         Schema::create('timelines', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('memory_id')
                ->constrained('memories')
                ->cascadeOnDelete();

            $table->foreignId('profile_id')
                ->constrained('profiles')
                ->cascadeOnDelete();

            // Timeline details
            $table->string('title');

            // Supports 1961, December 12th 1944, etc.
            $table->string('event_date', 100);

            $table->string('location')->nullable();

            $table->text('description')->nullable();

            // Timeline icon
            $table->string('icon')->default('star');

            // Optional image
            $table->string('image_path')->nullable();

            // Privacy
            $table->enum('visibility', [
                'public',
                'private'
            ])->default('public');

            // Ordering
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['profile_id', 'visibility']);
            $table->index(['memory_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timelines');
    }
};
