<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_diaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('entry_date')->unique(); // Hanya satu entri per hari per user
            $table->text('content');
            $table->text('summary')->nullable(); // Untuk rangkuman mingguan atau ringkasan per entri
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_diaries');
    }
};