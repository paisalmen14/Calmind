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
            // Ambil sesi yang relevan (aktif dan sudah selesai)
            $consultations = Consultation::where('psychologist_id', $user->id)
                                        ->whereIn('status', ['confirmed', 'completed']) // <-- PERUBAHAN DI SINI
                                        ->with('user') 
                                        ->latest('requested_start_time')
                                        ->get();
        }
        // Tampilkan view yang sama (chat.index), tapi sekarang datanya adalah daftar konsultasi
        return view('chat.index', compact('consultations'));
    }

    /**
     * Menampilkan halaman chat untuk sebuah sesi konsultasi.
     */
    public function show(Consultation $consultation)
    {
        $isArchived = false; // Default, chat tidak diarsipkan (aktif)

    // Coba akses sebagai chat aktif terlebih dahulu
    if (Gate::allows('access-consultation-chat', $consultation)) {
        // Akses diizinkan, biarkan $isArchived = false
    } 
    // Jika gagal, coba akses sebagai riwayat/arsip
    elseif (Gate::allows('view-chat-history', $consultation)) {
        $isArchived = true; // Set sebagai arsip (read-only)
    } 
    // Jika keduanya gagal, tolak akses
    else {
        return redirect()->route('consultations.history')
            ->with('error', 'Anda tidak memiliki akses ke sesi konsultasi ini.');
    }
        
        // Tentukan siapa lawan bicara
        $user = auth()->user();
        $contact = ($user->id === $consultation->user_id) ? $consultation->psychologist : $consultation->user;

        // Ambil riwayat pesan untuk sesi ini
        $messages = $consultation->chats()->orderBy('created_at', 'asc')->get();

        // Tandai pesan dari lawan bicara sebagai sudah dibaca
        $consultation->chats()
                     ->where('sender_id', $contact->id)
                     ->where('receiver_id', $user->id)
                     ->whereNull('read_at')
                     ->update(['read_at' => now()]);

         return view('chat.show', compact('consultation', 'contact', 'messages', 'isArchived'));
    }

    /**
     * Menyimpan pesan baru dalam sebuah sesi konsultasi.
     */
    public function store(Request $request, Consultation $consultation)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        if (Gate::denies('access-consultation-chat', $consultation)) {
            return back()->with('error', 'Tidak dapat mengirim pesan. Sesi belum dimulai atau sudah berakhir.');
        }

        // Tentukan penerima pesan secara otomatis dari objek konsultasi
        $user = auth()->user();
        $receiverId = ($user->id === $consultation->user_id) ? $consultation->psychologist_id : $consultation->user_id;

        $consultation->chats()->create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId, 
            'message' => $request->message,
        ]);

        return back();
    }
}