<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'target',
        'quota',
        'used_count',
        'min_purchase',
        'max_discount',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_until' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // === Relationships ===

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // === Validation ===

    /**
     * Check if the voucher can be used (general validity).
     */
    public function isValid(): bool
    {
        $now = Carbon::now();

        return $this->is_active
            && $this->used_count < $this->quota
            && $now->between($this->valid_from, $this->valid_until);
    }

    /**
     * Check if the voucher has already been used by a specific user.
     */
    public function hasBeenUsedBy(int $userId): bool
    {
        return $this->transactions()
            ->where('user_id', $userId)
            ->whereIn('status', ['paid'])
            ->exists();
    }

    /**
     * Check if the voucher is applicable for a given target and amount.
     */
    public function isApplicable(string $target, int $amount): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // Check target compatibility
        if ($this->target !== 'all' && $this->target !== $target) {
            return false;
        }

        // Check minimum purchase
        if ($amount < $this->min_purchase) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount (with cap via max_discount).
     */
    public function calculateDiscount(int $amount): int
    {
        if ($this->type === 'percentage') {
            $discount = (int) round($amount * $this->value / 100);

            // Apply max_discount cap if set
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }

            return $discount;
        }

        // Fixed amount: discount cannot exceed the total
        return min($this->value, $amount);
    }

    // === Helpers ===

    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) return 'Inactive';
        if ($this->used_count >= $this->quota) return 'Sold Out';
        if (Carbon::now()->gt($this->valid_until)) return 'Expired';
        if (Carbon::now()->lt($this->valid_from)) return 'Upcoming';
        return 'Active';
    }
}
