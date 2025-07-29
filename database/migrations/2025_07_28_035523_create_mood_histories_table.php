<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mood_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('emotion'); // e.g., 'Senang', 'Sedih'
            $table->date('tracked_at'); // Tanggal mood direkam
            $table->timestamps();

            $table->unique(['user_id', 'tracked_at']); // Kunci unik agar user hanya bisa input 1 mood per hari
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mood_histories');
    }
};
