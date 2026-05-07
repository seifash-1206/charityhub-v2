<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'goal_amount',
        'current_amount',
        'deadline',
        'image',
        'status',
    ];

    protected $casts = [
        'goal_amount'    => 'decimal:2',
        'current_amount' => 'decimal:2',
        'deadline'       => 'datetime',
    ];

    // ─────────────────────────────────────────────
    // BOOT — auto-generate slug
    // ─────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Campaign $campaign) {
            if (empty($campaign->slug)) {
                $campaign->slug = static::generateUniqueSlug($campaign->title);
            }
        });

        static::updating(function (Campaign $campaign) {
            if ($campaign->isDirty('title') && empty($campaign->slug)) {
                $campaign->slug = static::generateUniqueSlug($campaign->title);
            }
        });
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    // ─────────────────────────────────────────────
    // RELATIONSHIPS
    // ─────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function volunteers()
    {
        return $this->hasMany(Volunteer::class);
    }

    // ─────────────────────────────────────────────
    // ROUTE MODEL BINDING — use slug in URLs
    // ─────────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─────────────────────────────────────────────
    // BUSINESS LOGIC
    // ─────────────────────────────────────────────

    /** Progress percentage (0–100) */
    public function getProgressPercentage(): float
    {
        if ($this->goal_amount == 0) return 0;
        return min(($this->current_amount / $this->goal_amount) * 100, 100);
    }

    /** Total paid donations (live calculation) */
    public function getTotalDonationsAttribute(): float
    {
        return (float) $this->donations()->where('status', 'paid')->sum('amount');
    }

    /** Is the campaign goal reached? */
    public function isCompleted(): bool
    {
        return (float) $this->current_amount >= (float) $this->goal_amount;
    }

    /** Has the campaign deadline passed? */
    public function isExpired(): bool
    {
        return $this->deadline !== null && $this->deadline->isPast();
    }

    /** Is the campaign currently accepting donations? */
    public function isAcceptingDonations(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Recompute and persist the campaign status.
     * Rules:
     *  - expired   → deadline has passed
     *  - completed → current_amount >= goal_amount
     *  - active    → otherwise
     */
    public function updateStatus(): void
    {
        if ($this->isExpired()) {
            $newStatus = 'expired';
        } elseif ($this->isCompleted()) {
            $newStatus = 'completed';
        } else {
            $newStatus = 'active';
        }

        // Only write if status actually changed
        if ($this->status !== $newStatus) {
            $this->update(['status' => $newStatus]);
        }
    }

    // ─────────────────────────────────────────────
    // HELPERS FOR UI
    // ─────────────────────────────────────────────

    /** Tailwind badge classes for current status */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'active'    => 'bg-emerald-100 text-emerald-700',
            'completed' => 'bg-blue-100 text-blue-700',
            'expired'   => 'bg-red-100 text-red-600',
            'draft'     => 'bg-gray-100 text-gray-500',
            default     => 'bg-gray-100 text-gray-500',
        };
    }

    /** Tailwind dot color for status badge */
    public function getStatusDotClass(): string
    {
        return match ($this->status) {
            'active'    => 'bg-emerald-400',
            'completed' => 'bg-blue-400',
            'expired'   => 'bg-red-400',
            default     => 'bg-gray-400',
        };
    }

    // ─────────────────────────────────────────────
    // SCOPES
    // ─────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }
}