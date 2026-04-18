<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'synopsis',
        'genre',
        'director',
        'cast',
        'duration',
        'poster',
        'banner',
        'trailer_url',
        'rating',
        'release_date',
        'status',
        'age_rating',
    ];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'rating' => 'decimal:1',
        ];
    }

    // === Relationships ===

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    // === Scopes ===

    public function scopeNowPlaying($query)
    {
        return $query->where('status', 'now_playing');
    }

    public function scopeComingSoon($query)
    {
        return $query->where('status', 'coming_soon');
    }

    // === Helpers ===

    public function getDurationFormattedAttribute(): string
    {
        $hours = intdiv($this->duration, 60);
        $mins = $this->duration % 60;
        return "{$hours}h {$mins}m";
    }

    public function getPosterUrlAttribute(): string
    {
        if ($this->poster && file_exists(public_path('storage/' . $this->poster))) {
            return asset('storage/' . $this->poster);
        }
        return asset('images/placeholder-movie.jpg');
    }

    public function getBannerUrlAttribute(): string
    {
        if ($this->banner && file_exists(public_path('storage/' . $this->banner))) {
            return asset('storage/' . $this->banner);
        }
        return $this->poster_url;
    }
}
