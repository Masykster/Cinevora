<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Studio extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'cinema_id',
        'name',
        'type',
        'capacity',
        'rows',
        'cols',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Studio $studio) {
            $studio->capacity = $studio->rows * $studio->cols;
        });

        static::created(function (Studio $studio) {
            $seats = [];
            $now = now();

            for ($r = 0; $r < $studio->rows; $r++) {
                $rowLabel = chr(65 + $r); // A, B, C...
                for ($c = 1; $c <= $studio->cols; $c++) {
                    $seats[] = [
                        'studio_id' => $studio->id,
                        'row_label' => $rowLabel,
                        'seat_number' => $c,
                        'code' => $rowLabel . $c,
                        'type' => 'regular',
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            Seat::insert($seats);
        });
    }

    // === Relationships ===

    public function cinema(): BelongsTo
    {
        return $this->belongsTo(Cinema::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    // === Scopes ===

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // === Helpers ===

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'imax' => 'IMAX',
            'vip' => 'VIP',
            default => 'Regular',
        };
    }
}
