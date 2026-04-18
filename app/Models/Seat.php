<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    protected $fillable = [
        'studio_id',
        'row_label',
        'seat_number',
        'code',
        'type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // === Relationships ===

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // === Helpers ===

    /**
     * Check if this seat is booked for a specific schedule.
     */
    public function isBookedForSchedule(int $scheduleId): bool
    {
        return $this->tickets()
            ->where('schedule_id', $scheduleId)
            ->whereHas('transaction', fn ($q) => $q->whereIn('status', ['pending', 'paid']))
            ->exists();
    }
}
