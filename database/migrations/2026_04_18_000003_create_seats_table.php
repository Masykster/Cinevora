<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->string('row_label', 2); // A, B, C...
            $table->integer('seat_number'); // 1, 2, 3...
            $table->string('code', 5); // A1, A2, B1...
            $table->enum('type', ['regular', 'vip', 'disabled'])->default('regular');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['studio_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
