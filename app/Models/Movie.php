<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Movie extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'synopsis',
        'genre',
        'director',
        'cast',
        'cast_images',
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
            'cast_images' => 'array',
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
        return $query->where(function ($q) {
            $q->where('status', 'now_playing')
              ->orWhere(function ($sq) {
                  $sq->where('status', 'coming_soon')
                     ->where('release_date', '<=', now()->toDateString());
              });
        })->where('status', '!=', 'ended');
    }

    public function scopeComingSoon($query)
    {
        return $query->where('status', 'coming_soon')
            ->where('release_date', '>', now()->toDateString());
    }

    // === Helpers ===

    public function getIsNowPlayingAttribute(): bool
    {
        if ($this->status === 'ended') {
            return false;
        }

        return $this->status === 'now_playing'
            || ($this->status === 'coming_soon' && $this->release_date && $this->release_date->isPast());
    }

    public function getDurationFormattedAttribute(): string
    {
        $hours = intdiv($this->duration, 60);
        $mins = $this->duration % 60;
        return "{$hours}h {$mins}m";
    }

    public function getPosterUrlAttribute(): string
    {
        if ($this->poster) {
            if (str_starts_with($this->poster, 'http')) {
                return $this->poster;
            }
            return Storage::disk('supabase')->url($this->poster);
        }
        return asset('images/placeholder-movie.jpg');
    }

    public function getBannerUrlAttribute(): string
    {
        if ($this->banner) {
            if (str_starts_with($this->banner, 'http')) {
                return $this->banner;
            }
            return Storage::disk('supabase')->url($this->banner);
        }
        return $this->poster_url;
    }

    public function getCastListAttribute(): array
    {
        if (!$this->cast) {
            return [];
        }
        return array_map('trim', explode(',', $this->cast));
    }

    public static function getInitials(string $name): string
    {
        $words = explode(' ', trim($name));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= mb_substr($w, 0, 1, 'UTF-8');
            }
        }
        return mb_strtoupper(mb_substr($initials, 0, 2, 'UTF-8'), 'UTF-8');
    }
}
