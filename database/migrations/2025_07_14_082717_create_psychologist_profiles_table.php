<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psychologist_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //
            $table->string('profile_image_path')->nullable()->after('user_id'); //
            $table->unsignedInteger('price_per_hour')->default(100000); //
            $table->string('ktp_number')->comment('Nomor KTP'); //
            $table->string('university'); //
            $table->year('graduation_year'); //
            $table->string('certificate_path')->comment('Path ke file ijazah/sertifikat'); //
            $table->string('ktp_path')->comment('Path ke file KTP'); //
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psychologist_profiles');
    }
};