<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\MoodAnalysis;
use Illuminate\Validation\ValidationException;

class MoodAnalysisController extends Controller
{
  public function analyze(Request $request)
  {
    // 1. Validasi input dari frontend
    try {
      $validated = $request->validate([
        // Pastikan 'answers' adalah object dengan 42 key
        'answers' => 'required|array|size:42',
        'answers.Q1A' => 'required|integer|between:0,3',
        // ... (validasi bisa diperketat, tapi ini sudah cukup)
      ]);
    } catch (ValidationException $e) {
      return response()->json(['error' => 'Input tidak valid.', 'details' => $e->errors()], 422);
    }

    $answers = $validated['answers'];

    // 2. Panggil API microservice Flask
    try {
      // Pastikan server Flask (app.py) Anda sedang berjalan
      $flaskEndpoint = 'http://127.0.0.1:5000/analyze';

      $response = Http::timeout(15)->post($flaskEndpoint, ['answers' => $answers]);

      // 3. Proses respons dari Flask
      if ($response->successful()) {
        $result = $response->json();

        // 4. (Penting) Simpan hasil analisis ke database
        MoodAnalysis::create([
          // --- INI BAGIAN YANG DIPERBAIKI ---
          'user_id' => $request->user()->id,
          'prediction' => $result['prediction'],
          'recommendation' => $result['recommendation'],
          'confidence_scores' => $result['confidence'],
          'raw_answers' => $answers,
        ]);

        // 5. Kembalikan hasil ke frontend React
        return response()->json($result);
      } else {
        // Jika Flask error (misal: 500 internal server error di Flask)
        Log::error('Flask Service Error: ' . $response->body());
        return response()->json(['error' => 'Layanan analisis gagal memproses data Anda.'], 502); // 502 Bad Gateway
      }
    } catch (\Exception $e) {
      // Jika tidak bisa terhubung ke Flask (misal: Flask mati)
      Log::error('Connection to Flask service failed: ' . $e->getMessage());
      return response()->json(['error' => 'Layanan analisis tidak dapat dihubungi saat ini.'], 503); // 503 Service Unavailable
    }
  }
}
