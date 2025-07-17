<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon; // Pastikan ini ada

class ChatController extends Controller
{
    /**
     * Menampilkan daftar sesi konsultasi yang bisa di-chat.
     */
    public function index()
    {
        $user = Auth::user();
        $consultations = collect();

        if ($user->role === 'pengguna') {
            // Ambil semua konsultasi user yang sudah dikonfirmasi
            $consultations = $user->consultationsAsUser()
                                  ->where('status', 'confirmed')
                                  ->with('psychologist') // Eager load data psikolog
                                  ->latest('requested_start_time')
                                  ->get();
        } elseif ($user->role === 'psikolog') {
            // Ambil sesi yang relevan untuk psikolog:
            // - 'confirmed': untuk sesi yang akan datang atau sedang berjalan
            // - 'completed': untuk riwayat sesi yang sudah selesai
            // - 'pending_verification': untuk sesi yang menunggu verifikasi pembayaran (agar bisa melihat chat awal jika ada)
            $consultations = Consultation::where('psychologist_id', $user->id)
                                        ->whereIn('status', ['confirmed', 'completed', 'pending_verification'])
                                        ->with('user')
                                        ->latest('requested_start_time')
                                        ->get();
        }
        
        return view('chat.index', compact('consultations'));
    }

    /**
     * Menampilkan halaman chat untuk sebuah sesi konsultasi.
     */
    public function show(Consultation $consultation)
    {
        $user = Auth::user();

        // 1. Pastikan pengguna adalah bagian dari konsultasi ini
        if ($user->id !== $consultation->user_id && $user->id !== $consultation->psychologist_id) {
            return redirect()->route('consultations.history')
                ->with('error', 'Anda tidak memiliki akses ke sesi konsultasi ini.');
        }

        // Tentukan apakah sesi ini diizinkan untuk live chat atau hanya melihat riwayat
        $isLiveChatAllowed = Gate::allows('access-consultation-chat', $consultation);
        $isViewHistoryAllowed = Gate::allows('view-chat-history', $consultation);

        $isArchived = true; // Defaultnya adalah arsip (tidak bisa kirim pesan)

        if ($isLiveChatAllowed) {
            // Jika live chat diizinkan, maka ini adalah sesi live, bukan arsip
            $isArchived = false;
        } elseif ($isViewHistoryAllowed) {
            // Jika live chat TIDAK diizinkan, tapi melihat riwayat DIZINKAN, maka ini adalah arsip
            $isArchived = true;
        } else {
            // Jika TIDAK live chat dan TIDAK bisa melihat riwayat, maka ada beberapa kemungkinan:
            // 1. Sesi 'confirmed' tapi masih di masa depan (belum waktunya bahkan untuk arsip awal)
            // 2. Status konsultasi tidak diketahui/tidak diizinkan untuk diakses sama sekali.

            // Kita cek apakah sesi ini confirmed dan masih di masa depan
            if ($consultation->status === 'confirmed' && Carbon::now()->lessThan($consultation->requested_start_time)) {
                return redirect()->route('consultations.history')
                    ->with('error', 'Sesi konsultasi ini belum dimulai.');
            }

            // Untuk semua kasus lain di mana akses ditolak oleh kedua Gate
            return redirect()->route('consultations.history')
                ->with('error', 'Anda tidak memiliki akses ke sesi konsultasi ini.');
        }

        $contact = ($user->id === $consultation->user_id) ? $consultation->psychologist : $consultation->user;

        // Ambil semua pesan untuk konsultasi ini, diurutkan berdasarkan waktu
        $messages = $consultation->chats()->orderBy('created_at', 'asc')->get();

        // Tandai pesan dari lawan bicara sebagai sudah dibaca, hanya jika ini bukan tampilan arsip
        if (!$isArchived) { // Hanya tandai sebagai dibaca jika ini sesi live
            $consultation->chats()
                         ->where('sender_id', $contact->id)
                         ->where('receiver_id', $user->id)
                         ->whereNull('read_at')
                         ->update(['read_at' => now()]);
        }

        // Tentukan layout yang akan digunakan berdasarkan peran pengguna
        // Menggunakan alias komponen Blade yang benar 'app-layout'
        $layout = $user->role === 'psikolog' ? 'layouts.psychologist' : 'app-layout';

        return view('chat.show', compact('consultation', 'contact', 'messages', 'isArchived', 'layout'));
    }

    /**
     * Menyimpan pesan baru dalam sebuah sesi konsultasi.
     */
    public function store(Request $request, Consultation $consultation)
    {
        // Validasi pesan yang dikirim
        $request->validate(['message' => 'required|string|max:2000']);

        // Hanya izinkan pengiriman pesan jika pengguna memiliki akses live chat
        if (Gate::denies('access-consultation-chat', $consultation)) {
            return back()->with('error', 'Tidak dapat mengirim pesan. Sesi belum dimulai atau sudah berakhir.');
        }

        $user = Auth::user();
        // Tentukan ID penerima pesan
        $receiverId = ($user->id === $consultation->user_id) ? $consultation->psychologist_id : $consultation->user_id;

        // Buat pesan baru dalam chat konsultasi
        $consultation->chats()->create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        return back(); // Kembali ke halaman sebelumnya (halaman chat)
    }
}
