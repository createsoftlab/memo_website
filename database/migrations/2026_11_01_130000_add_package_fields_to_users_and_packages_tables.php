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
            if (!Schema::hasColumn('users', 'package_id')) {
                $table->foreignId('package_id')->nullable()->after('role')->constrained('packages')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'package_expires_at')) {
                $table->timestamp('package_expires_at')->nullable()->after('package_id');
            }
        });

        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'memories_limit')) {
                $table->integer('memories_limit')->default(1)->after('trial_days');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'package_id')) {
                $table->dropForeign(['package_id']);
                $table->dropColumn(['package_id']);
            }
            if (Schema::hasColumn('users', 'package_expires_at')) {
                $table->dropColumn(['package_expires_at']);
            }
        });

        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'memories_limit')) {
                $table->dropColumn(['memories_limit']);
            }
        });
    }
};

