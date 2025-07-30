<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\MoodHistory;
use Carbon\Carbon;

class EmotionController extends Controller
{
  public function detect(Request $request)
  {
    // 1. Validasi
    try {
      $request->validate(['image' => 'required|string']);
    } catch (ValidationException $e) {
      return response()->json(['error' => $e->getMessage()], 422);
    }

    // ======================================================
    // BAGIAN BARU: CEK APAKAH SUDAH ADA MOOD HARI INI
    // ======================================================
    $user = Auth::user();
    $alreadyCheckedToday = $user->moodHistories()
      ->whereDate('tracked_at', Carbon::today())
      ->exists();

    if ($alreadyCheckedToday) {
      // Jika sudah ada, kembalikan error.
      // Kode status 429 (Too Many Requests) cocok untuk kasus ini.
      return response()->json([
        'error' => 'Anda sudah melakukan cek mood hari ini. Silakan coba lagi besok!'
      ], 429);
    }
    // ======================================================
    // AKHIR BAGIAN BARU
    // ======================================================

    try {
      // 2. Ambil data gambar
      $base64Image = $request->input('image');

      // 3. Kirim request ke Flask API
      $response = Http::timeout(30)->post('http://127.0.0.1:5000/detect', [
        'image' => $base64Image,
      ]);

      // 4. Periksa jika request ke Flask gagal
      if ($response->failed()) {
        Log::error('Flask API Communication Error: ' . $response->body());
        return response()->json(['error' => 'Gagal terhubung dengan layanan analisis mood.'], 502);
      }

      // 5. Periksa jika Flask mengembalikan error
      if ($response->serverError() || $response->clientError()) {
        Log::error('Flask API Response Error: ' . $response->body());
        return response()->json(['error' => 'Layanan analisis mood mengalami masalah.'], 500);
      }

      // 6. Simpan hasil ke database (menggunakan 'create')
      $result = $response->json();
      $detectedEmotion = $result['emotion'] ?? null;

      if ($detectedEmotion) {
        // Gunakan 'create' karena kita sudah yakin belum ada data untuk hari ini
        $user->moodHistories()->create([
          'tracked_at' => Carbon::today(),
          'emotion' => $detectedEmotion,
        ]);
      }

      // 7. Kembalikan respons asli dari Flask
      return $response->json();
    } catch (\Exception $e) {
      Log::error('Unexpected Exception in EmotionController: ' . $e->getMessage() . ' on line ' . $e->getLine());
      return response()->json(['error' => 'Terjadi kesalahan tidak terduga di server.'], 500);
    }
  }
}
