<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Donation;
use App\Models\User;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'goal_amount',
        'current_amount',
        'deadline',
        'image',
        'status',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'deadline' => 'datetime',
    ];

    /**
     * 🔗 RELATIONSHIPS
     */

    // 👤 Campaign owner
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 💰 Campaign donations (CRITICAL 🔥)
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * 📊 BUSINESS LOGIC
     */

    // 🎯 Progress %
    public function getProgressPercentage(): float
    {
        if ($this->goal_amount == 0) return 0;

        return ($this->current_amount / $this->goal_amount) * 100;
    }

    // 💰 Total donated (calculated, not stored)
    public function getTotalDonationsAttribute()
    {
        return $this->donations()->sum('amount');
    }

    // 🟢 Check if campaign is completed
    public function isCompleted(): bool
    {
        return $this->current_amount >= $this->goal_amount;
    }
}