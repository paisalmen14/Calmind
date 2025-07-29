<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class MoodController extends Controller
{
    public function detectEmotion(Request $request)
    {
        $request->validate(['image' => 'required|image']);

        $today = Carbon::today()->toDateString();
        $userId = Auth::id();

        // Cek apakah pengguna sudah melakukan mood tracking hari ini
        $existingRecord = MoodHistory::where('user_id', $userId)
            ->whereDate('tracked_at', $today)
            ->first();

        if ($existingRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan Mood Tracker hari ini. Coba lagi besok!'
            ], 429); // 429 Too Many Requests
        }

        try {
            // Mengirim gambar ke API Python
            $response = Http::attach(
                'image',
                file_get_contents($request->file('image')->getRealPath()),
                'capture.jpg'
            )->post('http://127.0.0.1:5000/predict');

            if ($response->successful()) {
                $emotion = $response->json()['emotion'];

                // Simpan hasil ke database
                MoodHistory::create([
                    'user_id' => $userId,
                    'emotion' => $emotion,
                    'tracked_at' => $today,
                ]);

                return response()->json([
                    'success' => true,
                    'emotion' => $emotion
                ]);
            }

            return response()->json(['success' => false, 'message' => 'API deteksi gagal merespons.'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat terhubung ke layanan deteksi.'], 500);
        }
    }
}
