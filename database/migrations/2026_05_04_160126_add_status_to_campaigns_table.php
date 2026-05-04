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
        // ✅ Only add column if it does NOT exist
        if (!Schema::hasColumn('campaigns', 'status')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->string('status')->default('active')->after('goal_amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ✅ Only drop if exists (safe rollback)
        if (Schema::hasColumn('campaigns', 'status')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};