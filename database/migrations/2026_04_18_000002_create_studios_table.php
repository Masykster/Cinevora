<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g. "Studio 1", "IMAX Hall"
            $table->enum('type', ['regular', 'imax', 'vip'])->default('regular');
            $table->integer('capacity');
            $table->integer('rows')->default(10);
            $table->integer('cols')->default(12);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
