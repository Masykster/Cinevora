<?php
// Fix voucher dates to be currently valid
$now = now();
$validFrom = $now->copy()->subDays(7)->toDateString();
$validUntil = $now->copy()->addMonths(3)->toDateString();

// NONTONHEMAT - 15% discount on tickets
App\Models\Voucher::where('code', 'NONTONHEMAT')->update([
    'valid_from' => $validFrom,
    'valid_until' => $validUntil,
    'max_discount' => 25000,
]);

// MAKANGRATIS - Rp 20k off F&B
App\Models\Voucher::where('code', 'MAKANGRATIS')->update([
    'valid_from' => $validFrom,
    'valid_until' => $validUntil,
]);

// CINEVORA50 - 50% off everything (max discount 50k)
App\Models\Voucher::where('code', 'CINEVORA50')->update([
    'valid_from' => $validFrom,
    'valid_until' => $validUntil,
    'max_discount' => 50000,
]);

// WELCOMEBACK - already active, just extend
App\Models\Voucher::where('code', 'WELCOMEBACK')->update([
    'valid_until' => $validUntil,
]);

echo "All vouchers updated to be valid from $validFrom until $validUntil" . PHP_EOL;

// Verify
$vouchers = App\Models\Voucher::all();
foreach ($vouchers as $v) {
    echo $v->code . ' | ' . $v->valid_from->format('Y-m-d') . ' to ' . $v->valid_until->format('Y-m-d') . ' | isValid=' . ($v->isValid() ? 'Y' : 'N') . ' | status=' . $v->status_label . PHP_EOL;
}
