<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getProgressPercentage(): float
    {
        if ($this->goal_amount == 0) return 0;

        return ($this->current_amount / $this->goal_amount) * 100;
    }
}