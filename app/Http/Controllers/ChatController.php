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
                                        ->whereIn('status', ['confirmed', 'completed'])
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

        if (Gate::allows('access-consultation-chat', $consultation)) {
            // Akses diizinkan
        } 
        elseif (Gate::allows('view-chat-history', $consultation)) {
            $isArchived = true;
        } 
        else {
            // Jika keduanya gagal, tolak akses
            // Pastikan route 'consultations.history' ada atau ganti dengan route yang valid seperti 'dashboard'
            return redirect()->route('consultations.history')
                ->with('error', 'Anda tidak memiliki akses ke sesi konsultasi ini.');
        }
        
        // PERBAIKAN: Gunakan Auth::user() untuk mendapatkan user yang sedang login
        $user = Auth::user();
        $contact = ($user->id === $consultation->user_id) ? $consultation->psychologist : $consultation->user;

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

        $user = Auth::user();
        $receiverId = ($user->id === $consultation->user_id) ? $consultation->psychologist_id : $consultation->user_id;

        $consultation->chats()->create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId, 
            'message' => $request->message,
        ]);

        return back();
    }
}
