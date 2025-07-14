<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'pengguna', 'psikolog'])->default('pengguna')->after('password');
            $table->enum('psychologist_status', ['pending', 'approved', 'rejected'])->nullable()->after('role');
            $table->timestamp('membership_expires_at')->nullable()->after('remember_token');
            $table->foreignId('chosen_psychologist_id')->nullable()->after('psychologist_status')->constrained('users')->onDelete('set null'); //
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['chosen_psychologist_id']);
            $table->dropColumn(['role', 'psychologist_status', 'membership_expires_at', 'chosen_psychologist_id']);
        });
    }
};