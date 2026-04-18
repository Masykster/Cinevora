<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'NONTONHEMAT',
                'description' => 'Diskon 15% untuk pembelian tiket',
                'type' => 'percentage',
                'value' => 15,
                'target' => 'ticket',
                'quota' => 100,
                'min_purchase' => 50000,
                'valid_from' => Carbon::now()->subDays(5),
                'valid_until' => Carbon::now()->addDays(30),
            ],
            [
                'code' => 'MAKANGRATIS',
                'description' => 'Potongan Rp20.000 untuk pembelian F&B',
                'type' => 'fixed',
                'value' => 20000,
                'target' => 'fnb',
                'quota' => 50,
                'min_purchase' => 50000,
                'valid_from' => Carbon::now()->subDays(3),
                'valid_until' => Carbon::now()->addDays(14),
            ],
            [
                'code' => 'CINEVORA50',
                'description' => 'Diskon 50% maksimal Rp50.000 untuk semua pembelian',
                'type' => 'percentage',
                'value' => 50,
                'target' => 'all',
                'quota' => 20,
                'min_purchase' => 100000,
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addDays(7),
            ],
            [
                'code' => 'WELCOMEBACK',
                'description' => 'Potongan Rp10.000 untuk semua pembelian',
                'type' => 'fixed',
                'value' => 10000,
                'target' => 'all',
                'quota' => 200,
                'min_purchase' => 30000,
                'valid_from' => Carbon::now()->subDays(10),
                'valid_until' => Carbon::now()->addDays(60),
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create(array_merge($voucher, ['is_active' => true]));
        }
    }
}
