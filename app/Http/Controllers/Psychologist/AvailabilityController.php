<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    // Menampilkan halaman jadwal
    public function index()
    {
        $availabilities = Auth::user()->availabilities()->where('start_time', '>=', now())->orderBy('start_time')->get();
        return view('psychologist.availability.index', compact('availabilities'));
    }

    // Menambah jadwal baru
    public function store(Request $request)
    {
        $validated = $request->validate([
        'start_time' => 'required|date|after:now',
        'end_time' => 'required|date|after:start_time',
    ]);

    // Cek tumpang tindih jadwal (opsional, tapi sangat disarankan)
    
    Auth::user()->availabilities()->create($validated); // <-- Gunakan data yang sudah divalidasi

    return back()->with('success', 'Jadwal ketersediaan berhasil ditambahkan.');
    }

    // Menghapus jadwal
    public function destroy(Availability $availability)
    {
        // Pastikan psikolog hanya bisa menghapus jadwal miliknya
        if ($availability->psychologist_id !== Auth::id()) {
            abort(403);
        }

        // Jangan hapus jika sudah dibooking
        if ($availability->is_booked) {
            return back()->with('error', 'Tidak dapat menghapus jadwal yang sudah dipesan.');
        }

        $availability->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}