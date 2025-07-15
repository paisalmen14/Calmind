<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom username yang boleh null terlebih dahulu
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
        });

        // 2. Isi username untuk semua user yang sudah ada
        // Kita akan generate username dari email untuk memastikan keunikannya
        $users = User::whereNull('username')->get();
        foreach ($users as $user) {
            // Ambil bagian email sebelum @, dan tambahkan angka acak jika sudah ada
            $baseUsername = Str::slug(explode('@', $user->email)[0], '');
            $username = $baseUsername;
            $counter = 1;

            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            
            $user->username = $username;
            $user->save();
        }

        // 3. Setelah semua terisi, ubah kolom menjadi tidak boleh null dan harus unik
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};