<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('synopsis');
            $table->string('genre');
            $table->string('director');
            $table->string('cast')->nullable();
            $table->integer('duration'); // in minutes
            $table->string('poster')->nullable();
            $table->string('banner')->nullable();
            $table->string('trailer_url')->nullable();
            $table->decimal('rating', 3, 1)->default(0); // 0.0 - 10.0
            $table->date('release_date');
            $table->enum('status', ['now_playing', 'coming_soon', 'ended'])->default('coming_soon');
            $table->string('age_rating')->default('SU'); // SU, 13+, 17+, 21+
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
