<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/YYYY_MM_DD_HHMMSS_create_mood_analyses_table.php

    public function up(): void
    {
        Schema::create('mood_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('prediction'); // Contoh: 'Normal', 'Sedang', 'Parah'
            $table->text('recommendation');
            $table->json('confidence_scores'); // Menyimpan detail skor kepercayaan
            $table->json('raw_answers'); // Menyimpan 42 jawaban mentah pengguna
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_analyses');
    }
};
