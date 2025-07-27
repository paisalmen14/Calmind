<?php

namespace App\Http\Controllers;

use App\Models\DailyDiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Pastikan Carbon diimport

class DailyDiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diaries = Auth::user()->dailyDiaries()->latest('entry_date')->paginate(5);
        return view('daily_diary.index', compact('diaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Periksa apakah pengguna sudah membuat entri diary untuk hari ini
        $today = Carbon::today()->toDateString();
        $existingDiary = Auth::user()->dailyDiaries()->whereDate('entry_date', $today)->first();

        if ($existingDiary) {
            // Jika sudah ada, redirect ke halaman edit dengan pesan informasi
            return redirect()->route('daily-diary.edit', $existingDiary)->with('info', 'Anda sudah membuat entri diary untuk hari ini. Anda dapat mengeditnya di sini.');
        }

        return view('daily_diary.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255', // Tambahkan validasi untuk judul
            'content' => 'required|string|min:10',
        ]);

        $today = Carbon::today()->toDateString();
        $existingDiary = Auth::user()->dailyDiaries()->whereDate('entry_date', $today)->first();

        if ($existingDiary) {
            return back()->with('error', 'Anda sudah membuat entri diary untuk hari ini. Silakan edit.')->withInput();
        }

        DailyDiary::create([
            'user_id' => Auth::id(),
            'entry_date' => $today,
            'title' => $request->title, // Simpan judul
            'content' => $request->content,
        ]);

        return redirect()->route('daily-diary.index')->with('success', 'Diary berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyDiary $dailyDiary)
    {
        if ($dailyDiary->user_id !== Auth::id()) {
            abort(403);
        }
        return view('daily_diary.show', compact('dailyDiary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyDiary $dailyDiary)
    {
        if ($dailyDiary->user_id !== Auth::id()) {
            abort(403);
        }
        return view('daily_diary.edit', compact('dailyDiary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyDiary $dailyDiary)
    {
        if ($dailyDiary->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255', // Tambahkan validasi untuk judul
            'content' => 'required|string|min:10',
        ]);

        $dailyDiary->update([
            'title' => $request->title, // Update judul
            'content' => $request->content,
        ]);

        return redirect()->route('daily-diary.index')->with('success', 'Diary berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyDiary $dailyDiary)
    {
        if ($dailyDiary->user_id !== Auth::id()) {
            abort(403);
        }
        
        $dailyDiary->delete();

        return redirect()->route('daily-diary.index')->with('success', 'Diary berhasil dihapus!');
    }

    /**
     * Display the weekly summary of daily diaries.
     */
    public function weeklySummary()
    {
        $user = Auth::user();
        $diaries = $user->dailyDiaries()->latest('entry_date')->get();

        $today = Carbon::today();
        $startOfWeek = $today->startOfWeek(Carbon::MONDAY)->toDateString(); // Mulai dari Senin
        $endOfWeek = $today->endOfWeek(Carbon::SUNDAY)->toDateString(); // Berakhir di Minggu

        $weeklyDiaries = $diaries->filter(function($diary) use ($startOfWeek, $endOfWeek) {
            return $diary->entry_date->between($startOfWeek, $endOfWeek);
        });

        // Contoh ringkasan sederhana
        $summary = "Belum ada rangkuman minggu ini.";
        if ($weeklyDiaries->count() > 0) {
            $summary = "Anda telah menulis " . $weeklyDiaries->count() . " entri diary minggu ini. Teruslah menulis!";
        }

        return view('daily_diary.weekly_summary', compact('weeklyDiaries', 'summary', 'startOfWeek', 'endOfWeek'));
    }
}