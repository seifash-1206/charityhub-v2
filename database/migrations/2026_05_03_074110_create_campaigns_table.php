<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            // OWNER
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // BASIC INFO
            $table->string('title');
            $table->text('description');

            // MONEY
            $table->decimal('goal_amount', 10, 2);
            $table->decimal('current_amount', 10, 2)->default(0);

            // MEDIA
            $table->string('image')->nullable();

            // META
            $table->date('deadline')->nullable();

            $table->enum('status', ['draft', 'active', 'completed'])
                  ->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};