<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
        ];
    }

    // === Relationships ===

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // === Scopes ===

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // === Stock Management ===

    /**
     * Check if enough stock is available.
     */
    public function hasStock(int $quantity = 1): bool
    {
        // If stock tracking is disabled (stock = 0), always available
        if ($this->stock === 0) {
            return $this->is_available;
        }

        return $this->is_available && $this->stock >= $quantity;
    }

    /**
     * Decrement stock atomically at the database level to prevent race conditions.
     */
    public function decrementStock(int $quantity): bool
    {
        if ($this->stock === 0) {
            return true; // No stock tracking
        }

        $affected = static::where('id', $this->id)
            ->where('stock', '>=', $quantity)
            ->decrement('stock', $quantity);

        return $affected > 0;
    }

    /**
     * Increment stock back (on cancel/remove).
     */
    public function incrementStock(int $quantity): void
    {
        if ($this->stock === 0 && $this->getOriginal('stock') === 0) {
            return; // No stock tracking
        }

        $this->increment('stock', $quantity);
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
        return asset('images/placeholder-food.jpg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getStockLabelAttribute(): string
    {
        if ($this->stock === 0) {
            return 'Unlimited';
        }
        return (string) $this->stock;
    }
}
