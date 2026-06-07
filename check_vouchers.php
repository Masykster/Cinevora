<?php
$vouchers = App\Models\Voucher::all();
foreach ($vouchers as $v) {
    echo $v->id . ' | ' . $v->code . ' | ' . $v->type . ' | ' . $v->value . ' | target=' . $v->target . ' | quota=' . $v->quota . '/' . $v->used_count;
    echo ' | min=' . $v->min_purchase . ' | max_disc=' . $v->max_discount;
    echo ' | from=' . ($v->valid_from ? $v->valid_from->format('Y-m-d') : 'null') . ' | until=' . ($v->valid_until ? $v->valid_until->format('Y-m-d') : 'null');
    echo ' | active=' . ($v->is_active ? 'Y' : 'N');
    echo ' | isValid=' . ($v->isValid() ? 'Y' : 'N');
    echo ' | status=' . $v->status_label;
    echo PHP_EOL;
}
echo 'Total: ' . $vouchers->count() . PHP_EOL;
