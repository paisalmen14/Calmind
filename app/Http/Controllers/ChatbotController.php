<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View; // <-- Tambahkan ini

class ChatbotController extends Controller
{
    /**
     * Menampilkan halaman chatbot.
     */
    public function index(): View // <-- Tambahkan method ini
    {
        return view('chatbot.index');
    }

    /**
     * Mengirim pesan ke API chatbot.
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'history' => 'sometimes|array',
        ]);

        $userMessage = $validated['message'];
        $history = $validated['history'] ?? [];
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'API Key for Gemini is not configured.'], 500);
        }

        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}";

        $systemPrompt = "Anda adalah chatbot AI pendengar yang baik untuk kesehatan mental bernama 'TemanDengar'. Tugas Anda adalah memberikan dukungan emosional awal, mendengarkan keluh kesah pengguna, dan memberikan respons yang empatik, menenangkan, dan tidak menghakimi. JANGAN memberikan diagnosis medis atau resep obat. Selalu sertakan disclaimer di akhir percakapan bahwa 'Saya adalah AI dan bukan pengganti psikolog atau psikiater profesional. Jika Anda merasa butuh bantuan lebih lanjut, sangat disarankan untuk menghubungi tenaga profesional.' Fokus pada validasi perasaan pengguna.";

        $contents = [];
        foreach ($history as $turn) {
            $contents[] = [
                'role' => $turn['role'],
                'parts' => [['text' => $turn['text']]],
            ];
        }
        $contents[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];

        $payload = [
            'contents' => $contents,
            'systemInstruction' => [
                'parts' => ['text' => $systemPrompt]
            ]
        ];

        try {
            $response = Http::timeout(60)->post($apiUrl, $payload);

            if ($response->successful()) {
                $reply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak dapat memproses balasan saat ini.';
                return response()->json(['reply' => trim($reply)]);
            }

            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? 'Terjadi kesalahan pada layanan AI.';
            return response()->json(['error' => $errorMessage], $response->status());

        } catch (\Exception $e) {
            return response()->json(['error' => 'Tidak dapat terhubung ke layanan AI saat ini. Silakan coba lagi nanti.'], 503);
        }
    }
}