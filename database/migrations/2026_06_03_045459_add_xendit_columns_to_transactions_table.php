<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('xendit_invoice_id')->nullable()->after('status');
            $table->text('xendit_invoice_url')->nullable()->after('xendit_invoice_id');
            $table->string('payment_method')->nullable()->after('xendit_invoice_url');
            $table->string('booking_code', 10)->unique()->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['xendit_invoice_id', 'xendit_invoice_url', 'payment_method', 'booking_code']);
        });
    }
};
