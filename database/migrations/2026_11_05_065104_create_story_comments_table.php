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
        Schema::create('story_comments', function (Blueprint $table) {
    $table->id();

    // Memorial profile
    $table->foreignId('profile_id')
        ->constrained('profiles')
        ->cascadeOnDelete();

    // Story details
    $table->string('title')->nullable();
    $table->string('contributor_name')->nullable();
    $table->longText('content');

    // Relationship to memorial person
    $table->string('relationship')->nullable();

    // Publication status
    $table->enum('status', [
        'pending',
        'published',
        'rejected'
    ])->default('published');

    $table->timestamps();

    $table->index(['profile_id', 'status', 'created_at']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_comments');
    }
};
