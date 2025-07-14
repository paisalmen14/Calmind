<?php

// database/migrations/YYYY_MM_DD_XXXXXX_create_consultations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id(); //
            $table->foreignId('user_id')->constrained('users'); //
            $table->foreignId('psychologist_id')->constrained('users'); //
            $table->foreignId('availability_id')->nullable()->constrained('availabilities')->onDelete('set null'); //
            $table->dateTime('requested_start_time'); //
            $table->unsignedInteger('duration_minutes'); //
            $table->unsignedInteger('psychologist_price'); //
            $table->unsignedInteger('admin_fee'); //
            $table->unsignedInteger('total_payment'); //
            $table->string('status')->default('pending_payment'); //
            $table->timestamp('expires_at'); //
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};