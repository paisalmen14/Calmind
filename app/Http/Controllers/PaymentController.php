<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\User;
use App\Notifications\AdminPaymentPending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman untuk upload bukti bayar.
     */
    public function create(Consultation $consultation)
    {
        // Pastikan user yang benar yang mengakses
        if ($consultation->user_id !== auth()->id()) {
            abort(403);
        }

        // Pastikan belum lewat batas waktu atau statusnya salah
        if ($consultation->expires_at < now() || $consultation->status !== 'pending_payment') {
            return redirect()->route('consultations.history')->with('error', 'Reservasi ini sudah tidak valid atau kedaluwarsa.');
        }

        // Tampilkan view halaman pembayaran
        return view('consultations.payment.create', compact('consultation'));
    }

    /**
     * Menyimpan bukti pembayaran.
     */
    public function store(Request $request, Consultation $consultation)
    {
        $request->validate([
            'proof' => 'required|image|max:2048',
            'amount' => 'required|numeric',
        ]);

        // Pastikan user & statusnya benar sebelum menyimpan
        if ($consultation->user_id !== auth()->id() || $consultation->status !== 'pending_payment') {
             return redirect()->route('consultations.history')->with('error', 'Reservasi ini tidak valid.');
        }

        $consultation->status = 'pending_verification';
        $consultation->save();

        $path = $request->file('proof')->store('payment_proofs', 'public');
        
        $consultation->paymentConfirmation()->create([
            'user_id' => auth()->id(),
            'transaction_id' => 'TRX-' . time() . '-' . Str::upper(Str::random(6)),
            'payment_date' => now(),
            'amount' => $request->amount,
            'proof_path' => $path,
            'status' => 'pending',
        ]);

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new AdminPaymentPending($consultation));

        return redirect()->route('consultations.history')->with('success', 'Bukti pembayaran berhasil diunggah. Mohon tunggu verifikasi dari Admin.');
    }
}