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

        // 🔥 ADD ONLY IF NOT EXISTS
        if (!Schema::hasColumn('users', 'role')) {
            $table->string('role')->default('user')->after('email');
    }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 🔥 REMOVE ROLE COLUMN (rollback safety)
            $table->dropColumn('role');
        });
    }
};