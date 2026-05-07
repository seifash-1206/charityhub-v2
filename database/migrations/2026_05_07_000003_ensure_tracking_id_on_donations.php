<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure tracking_id column exists (it may not in older DBs)
        Schema::table('donations', function (Blueprint $table) {
            if (!Schema::hasColumn('donations', 'tracking_id')) {
                $table->string('tracking_id')->nullable()->unique()->after('transaction_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('tracking_id');
        });
    }
};
