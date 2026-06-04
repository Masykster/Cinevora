<?php

namespace App\Notifications;

use App\Models\CafeOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CafeOrderReadyNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CafeOrder $cafeOrder
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $invoice = $this->cafeOrder->transaction->invoice_number;
        $status = $this->cafeOrder->status;

        $messages = [
            'preparing' => "Pesanan {$invoice} sedang disiapkan. Mohon tunggu ya! 🍳",
            'ready'     => "Pesanan {$invoice} sudah siap! Silakan diambil di counter kafe. ✅",
            'completed' => "Pesanan {$invoice} selesai. Terima kasih! 📦",
        ];

        return [
            'cafe_order_id' => $this->cafeOrder->id,
            'transaction_id' => $this->cafeOrder->transaction_id,
            'invoice_number' => $invoice,
            'status' => $status,
            'message' => $messages[$status] ?? "Status pesanan {$invoice} diperbarui ke: {$status}",
        ];
    }
}
