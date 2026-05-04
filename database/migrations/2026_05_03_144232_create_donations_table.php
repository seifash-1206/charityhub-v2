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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // 🔗 Relationships
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('campaign_id')
                ->constrained()
                ->cascadeOnDelete();

            // 💰 Money
            $table->decimal('amount', 10, 2);

            // 📊 Status
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])
                ->default('pending');

            // 💳 Payment info
            $table->string('payment_method')->nullable(); // stripe / paymob
            $table->string('transaction_id')->nullable();

            // 🛡️ Prevent duplicate charges (important for Stripe later)
            $table->string('idempotency_key')->nullable()->unique();

            // 📝 Extra metadata (future proofing)
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};