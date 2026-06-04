<?php

namespace App\Events;

use App\Models\CafeOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CafeOrderStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public CafeOrder $cafeOrder
    ) {}
}
