<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cafe_orders', function (Blueprint $table) {
            $table->foreignId('cinema_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cafe_orders', function (Blueprint $table) {
            $table->dropForeign(['cinema_id']);
            $table->dropColumn('cinema_id');
        });
    }
};
