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
    // Log raw request for debugging
    Log::info('Raw request data received:', $request->all());

    // 1. Validasi input dari frontend - DIPERBAIKI
    try {
      // Validasi yang lebih fleksibel
      $validated = $request->validate([
        'answers' => 'required|array|size:42'
      ]);

      // Validasi setiap jawaban secara dinamis
      $answers = $validated['answers'];
      $expectedQuestions = [
        'Q1A',
        'Q2A',
        'Q3A',
        'Q4A',
        'Q5A',
        'Q6A',
        'Q7A',
        'Q8A',
        'Q9A',
        'Q10A',
        'Q11A',
        'Q12A',
        'Q13A',
        'Q14A',
        'Q15A',
        'Q16A',
        'Q17A',
        'Q18A',
        'Q19A',
        'Q20A',
        'Q21A',
        'Q22A',
        'Q23A',
        'Q24A',
        'Q25A',
        'Q26A',
        'Q27A',
        'Q28A',
        'Q29A',
        'Q30A',
        'Q31A',
        'Q32A',
        'Q33A',
        'Q34A',
        'Q35A',
        'Q36A',
        'Q37A',
        'Q38A',
        'Q39A',
        'Q40A',
        'Q41A',
        'Q42A'
      ];

      // Periksa apakah semua pertanyaan ada dan nilainya valid
      foreach ($expectedQuestions as $question) {
        if (!array_key_exists($question, $answers)) {
          Log::error("Missing question: $question");
          return response()->json([
            'error' => "Pertanyaan $question tidak ditemukan dalam jawaban."
          ], 422);
        }

        $value = $answers[$question];
        if (!is_numeric($value) || $value < 0 || $value > 3) {
          Log::error("Invalid answer for $question: $value");
          return response()->json([
            'error' => "Jawaban untuk $question tidak valid. Harus antara 0-3."
          ], 422);
        }

        // Pastikan nilai adalah integer
        $answers[$question] = (int) $value;
      }

      Log::info('Validation passed. Total answers:', ['count' => count($answers)]);
    } catch (ValidationException $e) {
      Log::error('Validation failed:', $e->errors());
      return response()->json([
        'error' => 'Input tidak valid.',
        'details' => $e->errors()
      ], 422);
    }
    // 2. Panggil API microservice Flask
    try {
      $flaskEndpoint = 'http://127.0.0.1:5000/analyze';

      $payload = ['answers' => $answers];
      Log::info('Sending to Flask:', $payload);

      $response = Http::timeout(15)->post($flaskEndpoint, $payload);

      Log::info('Flask response status:', ['status' => $response->status()]);
      Log::info('Flask response body:', ['body' => $response->body()]);

      // 3. Proses respons dari Flask
      if ($response->successful()) {
        $result = $response->json();
        Log::info('Flask analysis result:', $result);

        // 4. Simpan hasil analisis ke database
        try {
          MoodAnalysis::create([
            'user_id' => $request->user()->id,
            'prediction' => $result['prediction'],
            'recommendation' => $result['recommendation'],
            'confidence_scores' => $result['confidence'],
            'raw_answers' => $answers,
          ]);

          Log::info('Analysis saved to database successfully');
        } catch (\Exception $dbError) {
          Log::error('Failed to save to database:', ['error' => $dbError->getMessage()]);
          // Continue anyway - don't fail the entire request for database issues
        }

        // 5. Kembalikan hasil ke frontend
        return response()->json($result);
      } else {
        // Jika Flask error
        Log::error('Flask Service Error:', [
          'status' => $response->status(),
          'body' => $response->body()
        ]);
        return response()->json([
          'error' => 'Layanan analisis gagal memproses data Anda.',
          'details' => $response->body()
        ], 502);
      }
    } catch (\Exception $e) {
      // Jika tidak bisa terhubung ke Flask
      Log::error('Connection to Flask service failed:', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
      return response()->json([
        'error' => 'Layanan analisis tidak dapat dihubungi saat ini.',
        'details' => $e->getMessage()
      ], 503);
    }
  }
}
