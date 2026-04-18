<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->date('show_date');
            $table->time('show_time');
            $table->integer('price_weekday');
            $table->integer('price_weekend');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['movie_id', 'show_date']);
            $table->index(['studio_id', 'show_date', 'show_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
