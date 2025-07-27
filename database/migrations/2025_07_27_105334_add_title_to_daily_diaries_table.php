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
        Schema::table('daily_diaries', function (Blueprint $table) {
            // Tambahkan kolom 'title' setelah 'entry_date'
            $table->string('title')->after('entry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_diaries', function (Blueprint $table) {
            // Hapus kolom 'title' jika migrasi di-rollback
            $table->dropColumn('title');
        });
    }
};