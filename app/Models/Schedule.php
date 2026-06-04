<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Schedule extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'movie_id',
        'studio_id',
        'show_date',
        'show_time',
        'price_weekday',
        'price_weekend',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'show_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // === Relationships ===

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class)->withTrashed();
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class)->withTrashed();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // === Scopes ===

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('show_date', '>=', now()->toDateString());
    }

    public function scopeBookable($query)
    {
        $cutoff = now()->addMinutes(5)->toDateTimeString();
        return $query->where('is_active', true)
            ->whereRaw("CONCAT(show_date, ' ', show_time) >= ?", [$cutoff]);
    }

    // === Helpers ===

    public function getIsBookableAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $showDatetime = Carbon::parse($this->show_date->format('Y-m-d') . ' ' . $this->show_time);
        return now()->addMinutes(5)->lessThanOrEqualTo($showDatetime);
    }

    /**
     * Get the applicable price based on the show date (weekday vs weekend).
     */
    public function getPriceAttribute(): int
    {
        $dayOfWeek = Carbon::parse($this->show_date)->dayOfWeek;
        // Saturday = 6, Sunday = 0
        return in_array($dayOfWeek, [0, 6]) ? $this->price_weekend : $this->price_weekday;
    }

    public function getShowTimeFormattedAttribute(): string
    {
        return Carbon::parse($this->show_time)->format('H:i');
    }

    /**
     * Get booked seat IDs for this schedule.
     */
    public function getBookedSeatIds(): array
    {
        return $this->tickets()
            ->whereHas('transaction', fn ($q) => $q->whereIn('status', ['pending', 'paid']))
            ->pluck('seat_id')
            ->toArray();
    }

    /**
     * Get available seats count.
     */
    public function getAvailableSeatsCountAttribute(): int
    {
        $totalSeats = $this->studio->seats()->where('is_active', true)->count();
        $bookedSeats = count($this->getBookedSeatIds());
        return $totalSeats - $bookedSeats;
    }
}
