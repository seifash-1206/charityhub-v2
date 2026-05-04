<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',
        'campaign_id',
        'amount',
        'status',
        'payment_method',
        'transaction_id',
        'idempotency_key',
        'meta',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
    ];

    /**
     * Relationships
     */

    // 🔗 Donation belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Donation belongs to a campaign
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Scopes (🔥 cleaner queries)
     */

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Helpers (clean logic checks)
     */

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * 🔐 Idempotency helper (VERY IMPORTANT)
     */
    public static function alreadyProcessed($transactionId): bool
    {
        return self::where('transaction_id', $transactionId)
            ->where('status', 'paid')
            ->exists();
    }
}