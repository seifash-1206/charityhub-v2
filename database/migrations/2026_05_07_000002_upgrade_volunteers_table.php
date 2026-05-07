<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('id');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active')->after('notes');
            $table->enum('availability', ['weekdays', 'weekends', 'both', 'flexible'])->default('flexible')->after('status');
            $table->decimal('hours_logged', 8, 2)->default(0)->after('availability');
            $table->text('skills')->nullable()->after('hours_logged');
        });
    }

    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'status', 'availability', 'hours_logged', 'skills']);
        });
    }
};
