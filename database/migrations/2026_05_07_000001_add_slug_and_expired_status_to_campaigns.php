<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add slug to campaigns
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('title');
        });

        // Modify status enum to include 'expired'
        Schema::table('campaigns', function (Blueprint $table) {
            $table->enum('status', ['draft', 'active', 'completed', 'expired'])
                  ->default('active')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->enum('status', ['draft', 'active', 'completed'])
                  ->default('active')
                  ->change();
        });
    }
};
