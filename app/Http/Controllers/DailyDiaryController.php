<?php

namespace App\Http\Controllers;

use App\Models\DailyDiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailyDiaryController extends Controller
{
    public function index()
    {
        $diaries = Auth::user()->dailyDiaries()->latest('entry_date')->paginate(10);
        return view('daily_diary.index', compact('diaries'));
    }

    public function create()
    {
        $today = Carbon::today()->toDateString();
        $existingDiary = Auth::user()->dailyDiaries()->whereDate('entry_date', $today)->first();

        if ($existingDiary) {
            return redirect()->route('daily-diary.edit', $existingDiary)->with('info', 'Anda sudah membuat entri diary untuk hari ini. Silakan edit.');
        }

        return view('daily_diary.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:10',
        ]);

        $today = Carbon::today()->toDateString();
        $existingDiary = Auth::user()->dailyDiaries()->whereDate('entry_date', $today)->first();

        if ($existingDiary) {
            return back()->with('error', 'Anda sudah membuat entri diary untuk hari ini.')->withInput();
        }

        DailyDiary::create([
            'user_id' => Auth::id(),
            'entry_date' => $today,
            'content' => $request->content,
        ]);

        return redirect()->route('daily-diary.index')->with('success', 'Diary berhasil disimpan!');
    }

    public function edit(DailyDiary $dailyDiary)
    {
        if ($dailyDiary->user_id !== Auth::id()) {
            abort(403);
        }
        return view('daily_diary.edit', compact('dailyDiary'));
    }

    public function update(Request $request, DailyDiary $dailyDiary)
    {
        if ($dailyDiary->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|min:10',
        ]);

        $dailyDiary->update([
            'content' => $request->content,
        ]);

        return redirect()->route('daily-diary.index')->with('success', 'Diary berhasil diperbarui!');
    }

    public function show(DailyDiary $dailyDiary)
    {
        if ($dailyDiary->user_id !== Auth::id()) {
            abort(403);
        }
        return view('daily_diary.show', compact('dailyDiary'));
    }

    public function destroy(DailyDiary $dailyDiary)
    {
        if ($dailyDiary->user_id !== Auth::id()) {
            abort(403);
        }
        $dailyDiary->delete();
        return back()->with('success', 'Diary berhasil dihapus!');
    }

    /**
     * Menghasilkan rangkuman diary dari minggu sebelumnya.
     */
    public function generateWeeklySummary()
    {
        $startDate = Carbon::now()->subWeek()->startOfWeek();
        $endDate = Carbon::now()->subWeek()->endOfWeek();

        $weeklyDiaries = Auth::user()->dailyDiaries()
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->orderBy('entry_date') // Mengurutkan berdasarkan tanggal
            ->get();

        $summaryContent = "";
        foreach ($weeklyDiaries as $diary) {
            // Menggunakan format yang lebih deskriptif
            $summaryContent .= "Pada tanggal " . $diary->entry_date->isoFormat('dddd, D MMMM YYYY') . ", saya merasa:\n";
            $summaryContent .= $diary->content . "\n\n";
        }

        // Menambahkan pesan jika tidak ada diary
        if (empty(trim($summaryContent))) {
            $summaryContent = "Anda tidak memiliki catatan diary pada minggu lalu.";
        }

        // Anda bisa memproses $summaryContent dengan AI (Gemini) untuk ringkasan yang lebih baik
        // Untuk saat ini, kita hanya akan menampilkannya.
        return view('daily_diary.weekly_summary', compact('weeklyDiaries', 'summaryContent', 'startDate', 'endDate'));
    }
}
