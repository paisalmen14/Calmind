<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $isArchived = false;

        // Cek apakah pengguna memiliki akses live chat
        if (Gate::allows('access-consultation-chat', $consultation)) {
            // Jika diizinkan, ini adalah sesi live chat
            $isArchived = false; 
        } 
        // Jika tidak ada akses live chat, cek apakah pengguna bisa melihat riwayat chat
        elseif (Gate::allows('view-chat-history', $consultation)) {
            // Jika diizinkan, ini adalah tampilan riwayat chat
            $isArchived = true;
        } 
        else {
            // Jika keduanya gagal, tolak akses dan arahkan kembali
            return redirect()->route('consultations.history')
                ->with('error', 'Anda tidak memiliki akses ke sesi konsultasi ini.');
        }
        
        $user = Auth::user();
        // Tentukan kontak (pihak lain dalam chat) berdasarkan siapa yang sedang login
        $contact = ($user->id === $consultation->user_id) ? $consultation->psychologist : $consultation->user;

        // Ambil semua pesan untuk konsultasi ini, diurutkan berdasarkan waktu
        $messages = $consultation->chats()->orderBy('created_at', 'asc')->get();

        // Tandai pesan dari lawan bicara sebagai sudah dibaca, hanya jika ini bukan tampilan arsip
        // Ini mencegah pesan di arsip ditandai ulang setiap kali dilihat
        if (!$isArchived) {
            $consultation->chats()
                         ->where('sender_id', $contact->id)
                         ->where('receiver_id', $user->id)
                         ->whereNull('read_at')
                         ->update(['read_at' => now()]);
        }

        // Tentukan layout yang akan digunakan berdasarkan peran pengguna
        $layout = $user->role === 'psikolog' ? 'layouts.psychologist' : 'layouts.app';

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
