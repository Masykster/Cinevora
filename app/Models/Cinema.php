<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Cinema extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'city',
        'address',
        'description',
        'image',
        'phone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // === Relationships ===

    public function studios(): HasMany
    {
        return $this->hasMany(Studio::class);
    }

    // === Scopes ===

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // === Helpers ===

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            return Storage::disk('supabase')->url($this->image);
        }
        return asset('images/placeholder-cinema.jpg');
    }
}
