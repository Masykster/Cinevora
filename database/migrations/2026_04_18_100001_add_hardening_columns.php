<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. SoftDeletes on master data tables
        Schema::table('movies', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('cinemas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('studios', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
            $table->integer('stock')->default(0)->after('price');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->softDeletes();
            $table->integer('max_discount')->nullable()->after('min_purchase');
        });

        // 2. Transaction expires_at for temporary seat reservation
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('paid_at');
        });

    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('max_discount');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('stock');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('studios', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('cinemas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
