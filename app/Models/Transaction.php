<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'voucher_id',
        'invoice_number',
        'total',
        'discount',
        'grand_total',
        'status',
        'paid_at',
        'expires_at',
        'xendit_invoice_id',
        'xendit_invoice_url',
        'payment_method',
        'booking_code',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    // === Relationships ===

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class)->withTrashed();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cafeOrder(): HasOne
    {
        return $this->hasOne(CafeOrder::class);
    }

    // === Scopes ===

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')
            ->where('expires_at', '<', now());
    }

    // === Reservation Logic ===

    /**
     * Check if this pending transaction has expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'pending'
            && $this->expires_at
            && $this->expires_at->isPast();
    }

    /**
     * Get the remaining seconds before expiry.
     */
    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->expires_at || $this->status !== 'pending') {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($this->expires_at, false));
    }

    // === Helpers ===

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        return "{$prefix}-{$date}-{$random}";
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    public static function generateBookingCode(): string
    {
        do {
            $code = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (static::where('booking_code', $code)->exists());

        return $code;
    }

    public function getTicketTotalAttribute(): int
    {
        return $this->tickets->sum('price');
    }

    public function getFnbTotalAttribute(): int
    {
        return $this->orderItems->sum('subtotal');
    }
}
