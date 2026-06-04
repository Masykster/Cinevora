<?php

namespace App\Listeners;

use App\Events\CafeOrderStatusChanged;
use App\Notifications\CafeOrderReadyNotification;

class SendCafeOrderNotification
{
    public function handle(CafeOrderStatusChanged $event): void
    {
        $cafeOrder = $event->cafeOrder;
        $user = $cafeOrder->transaction->user;

        // Notify the customer about their order status change
        $user->notify(new CafeOrderReadyNotification($cafeOrder));
    }
}
