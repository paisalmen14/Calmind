<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\MoodHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar artikel (Halaman Beranda Anda).
     */
    public function index()
    {
        $articles = Article::latest()->paginate(10);

        // --- AWAL LOGIKA STATISTIK MOOD YANG DIPERBAIKI ---
        $moodHistories = collect(); // Diperlukan untuk Rata-rata Mood
        $moodStats = collect();     // Diperlukan untuk Riwayat dan Chart
        $averageMood = 'Netral';

        if (Auth::check()) {
            $user = Auth::user();
            $today = Carbon::today();
            $startOfWeek = $today->copy()->startOfWeek(Carbon::SUNDAY);
            $endOfWeek = $today->copy()->endOfWeek(Carbon::SATURDAY);

            // 1. Ambil data mood minggu ini dari database
            $moodHistories = $user->moodHistories()
                ->whereBetween('tracked_at', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->get();

            // 2. Indeks data berdasarkan nama hari B.Inggris (Sun, Mon, etc.) agar mudah dicari
            $moodsByKey = $moodHistories->keyBy(function ($item) {
                return Carbon::parse($item->tracked_at)->format('D');
            });

            // 3. Siapkan array untuk memetakan nama hari dari B.Inggris ke B.Indonesia
            $dayMap = [
                'Sun' => 'Min',
                'Mon' => 'Sen',
                'Tue' => 'Sel',
                'Wed' => 'Rab',
                'Thu' => 'Kam',
                'Fri' => 'Jum',
                'Sat' => 'Sab'
            ];

            // 4. Buat koleksi statistik yang *pasti* berurutan
            $moodStats = collect($dayMap)->map(function ($indonesianDay, $englishDay) use ($moodsByKey) {
                // Cari mood berdasarkan key hari B.Inggris
                $mood = $moodsByKey->get($englishDay);
                // Kembalikan emosi jika ada, atau null jika tidak ada
                return $mood ? $mood->emotion : null;
            });

            // 5. Hitung rata-rata mood
            if ($moodHistories->isNotEmpty()) {
                $averageMood = $moodHistories->groupBy('emotion')->map->count()->sortDesc()->keys()->first();
            }
        }
        // --- AKHIR LOGIKA STATISTIK MOOD ---

        // Kirim semua data yang diperlukan ke view
        return view('articles.index', compact('articles', 'moodHistories', 'moodStats', 'averageMood'));
    }

    /**
     * Menampilkan detail satu artikel.
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }
}
