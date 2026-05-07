<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'campaign_id',
        'notes',
        'status',
        'availability',
        'hours_logged',
        'skills',
    ];

    protected $casts = [
        'hours_logged' => 'decimal:2',
    ];

    // ─────────────────────────────────────────────
    // RELATIONSHIPS
    // ─────────────────────────────────────────────

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─────────────────────────────────────────────
    // SCOPES
    // ─────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ─────────────────────────────────────────────
    // HELPERS FOR UI
    // ─────────────────────────────────────────────

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'active'   => 'bg-emerald-100 text-emerald-700',
            'pending'  => 'bg-yellow-100 text-yellow-700',
            'inactive' => 'bg-gray-100 text-gray-500',
            default    => 'bg-gray-100 text-gray-500',
        };
    }

    public function getAvailabilityLabel(): string
    {
        return match ($this->availability) {
            'weekdays' => 'Weekdays',
            'weekends' => 'Weekends',
            'both'     => 'Weekdays & Weekends',
            'flexible' => 'Flexible',
            default    => 'Flexible',
        };
    }
}