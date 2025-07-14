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
        Schema::create('psychologist_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // PERBAIKAN: Menghapus semua ->after(...)
            $table->string('profile_image_path')->nullable();
            $table->unsignedInteger('price_per_hour')->default(100000);
            $table->string('specialization')->nullable(); // Menambahkan kolom spesialisasi
            $table->string('ktp_number')->comment('Nomor KTP');
            $table->string('university');
            $table->year('graduation_year');
            $table->string('certificate_path')->comment('Path ke file ijazah/sertifikat');
            $table->string('ktp_path')->comment('Path ke file KTP');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychologist_profiles');
    }
};
