<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'transaction_id',
        'schedule_id',
        'seat_id',
        'price',
        'barcode',
    ];

    // === Relationships ===

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class)->withTrashed();
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    // === Helpers ===

    public static function generateBarcode(): string
    {
        return 'TKT-' . strtoupper(bin2hex(random_bytes(6)));
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
