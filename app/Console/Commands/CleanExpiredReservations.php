<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanExpiredReservations extends Command
{
    protected $signature = 'reservations:clean';
    protected $description = 'Cancel expired pending transactions and release their seats/stock.';

    public function handle(): void
    {
        $expired = Transaction::expired()->with('orderItems.product')->get();

        if ($expired->isEmpty()) {
            $this->info('No expired reservations found.');
            return;
        }

        $count = 0;

        foreach ($expired as $transaction) {
            DB::transaction(function () use ($transaction) {
                // Restore F&B stock
                foreach ($transaction->orderItems as $item) {
                    $item->product->incrementStock($item->quantity);
                }

                $transaction->update([
                    'status' => 'cancelled',
                    'expires_at' => null,
                ]);
            });

            $count++;
        }

        $this->info("Cleaned {$count} expired reservation(s).");
    }
}
