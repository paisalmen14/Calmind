<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoodController extends Controller
{
    public function detectEmotion(Request $request)
    {
        $request->validate(['image' => 'required|image']);

        try {
            // Mengirim gambar ke API Python yang sedang berjalan
            $response = Http::attach(
                'image',
                file_get_contents($request->file('image')->getRealPath()),
                'capture.jpg'
            )->post('http://127.0.0.1:5000/predict'); // <-- Titik penghubungnya di sini

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'emotion' => $response->json()['emotion']
                ]);
            }

            return response()->json(['success' => false, 'message' => 'API deteksi gagal merespons.'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat terhubung ke layanan deteksi.'], 500);
        }
    }
}
