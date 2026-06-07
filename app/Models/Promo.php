<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Promo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'image_path',
        'link_url',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    // === Scopes ===

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // === Helpers ===

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            // Full URL already (http/https)
            if (str_starts_with($this->image_path, 'http')) {
                return $this->image_path;
            }
            // Supabase relative path (e.g. "promos/xxx.jpg")
            return Storage::disk('supabase')->url($this->image_path);
        }
        return '/images/placeholder-promo.jpg';
    }
}
