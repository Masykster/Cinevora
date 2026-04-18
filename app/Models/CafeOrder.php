<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CafeOrder extends Model
{
    protected $fillable = [
        'transaction_id',
        'status',
        'notes',
        'prepared_at',
        'ready_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'prepared_at' => 'datetime',
            'ready_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // === Relationships ===

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    // === Helpers ===

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'preparing' => 'Diproses',
            'ready' => 'Siap Diambil',
            'completed' => 'Selesai',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'preparing' => 'blue',
            'ready' => 'green',
            'completed' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Progress the order to the next status.
     */
    public function advanceStatus(): void
    {
        $next = match ($this->status) {
            'pending' => 'preparing',
            'preparing' => 'ready',
            'ready' => 'completed',
            default => null,
        };

        if ($next) {
            $this->status = $next;

            match ($next) {
                'preparing' => $this->prepared_at = now(),
                'ready' => $this->ready_at = now(),
                'completed' => $this->completed_at = now(),
                default => null,
            };

            $this->save();
        }
    }
}
