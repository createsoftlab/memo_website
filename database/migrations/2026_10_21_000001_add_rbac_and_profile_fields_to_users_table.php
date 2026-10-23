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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('country');
            }
            if (!Schema::hasColumn('users', 'registration_ip')) {
                $table->string('registration_ip', 45)->nullable()->after('state');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'suspended', 'banned'])->default('active')->after('registration_ip');
            }
            if (!Schema::hasColumn('users', 'is_memory_creator')) {
                $table->boolean('is_memory_creator')->default(false)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['country', 'state', 'registration_ip', 'status', 'is_memory_creator'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
