<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memory_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_id')->constrained('memories')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('message');
            $table->string('status')->default('active'); // active හෝ pending (අවශ්‍ය නම් approval සඳහා)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memory_messages');
    }
};
