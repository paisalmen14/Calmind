<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\MoodHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(10);

        $moodHistories = collect();
        $moodStats = collect();
        $averageMood = 'Netral';

        if (Auth::check()) {
            $user = Auth::user();
            $today = Carbon::today();
            $startOfWeek = $today->copy()->startOfWeek(Carbon::SUNDAY);
            $endOfWeek = $today->copy()->endOfWeek(Carbon::SATURDAY);

            $moodHistories = $user->moodHistories()
                ->whereBetween('tracked_at', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->get();

            $moodsByKey = $moodHistories->keyBy(function ($item) {
                return Carbon::parse($item->tracked_at)->format('D');
            });

            // ======================================================
            // AWAL DARI BLOK LOGIKA YANG DIPERBAIKI
            // ======================================================

            // Definisikan level untuk setiap emosi (1=rendah, 4=tinggi)
            $moodLevels = ['Marah' => 1, 'Jijik' => 2, 'Takut' => 2, 'Sedih' => 2, 'Terkejut' => 3, 'Netral' => 3, 'Senang' => 4];

            // Peta nama hari dari Inggris ke Indonesia
            $dayMap = ['Sun' => 'Min', 'Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab'];

            // Buat koleksi statistik yang lebih terstruktur
            $moodStats = collect($dayMap)->map(function ($indonesianDay, $englishDay) use ($moodsByKey, $moodLevels) {
                $mood = $moodsByKey->get($englishDay);
                $emotion = $mood ? $mood->emotion : null;

                return [
                    'day' => $indonesianDay, // Nama hari dalam B. Indonesia
                    'emotion' => $emotion,   // Nama emosi (string) atau null
                    'level' => $moodLevels[$emotion] ?? 0.2, // Level numerik untuk diagram (0.2 untuk hari kosong)
                ];
            });

            // Hitung rata-rata mood (logika ini tetap sama)
            if ($moodHistories->isNotEmpty()) {
                $averageMood = $moodHistories->groupBy('emotion')->map->count()->sortDesc()->keys()->first();
            }
            // ======================================================
            // AKHIR DARI BLOK LOGIKA YANG DIPERBAIKI
            // ======================================================
        }

        return view('articles.index', compact('articles', 'moodHistories', 'moodStats', 'averageMood'));
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }
}
