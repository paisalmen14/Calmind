<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Notifications\ConsultationVerifiedPsychologist;
use App\Notifications\ConsultationVerifiedUser;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Menampilkan daftar pembayaran yang perlu diverifikasi.
     */
    public function index()
    {
        // TODO: Buat view di resources/views/admin/verifications/index.blade.php
        $consultations = Consultation::where('status', 'pending_verification')
                                     ->with(['user', 'psychologist', 'paymentConfirmation'])
                                     ->paginate(10);
        return view('admin.verifications.index', compact('consultations'));
    }

    /**
     * Menyetujui pembayaran.
     */
    public function approve(Consultation $consultation)
    {
        $consultation->status = 'confirmed';
        $consultation->paymentConfirmation->status = 'approved';
        $consultation->save();
        $consultation->paymentConfirmation->save();

        // Kirim notifikasi ke pengguna dan psikolog
        $consultation->user->notify(new ConsultationVerifiedUser($consultation));
        $consultation->psychologist->notify(new ConsultationVerifiedPsychologist($consultation));

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Menolak pembayaran.
     */
    public function reject(Request $request, Consultation $consultation)
    {
        $request->validate(['rejection_reason' => 'required|string|max:255']);

        $consultation->status = 'payment_rejected';
        $consultation->paymentConfirmation->status = 'rejected';
        $consultation->paymentConfirmation->admin_notes = $request->rejection_reason;
        $consultation->save();
        $consultation->paymentConfirmation->save();

        // Opsional: Kirim notifikasi penolakan ke pengguna
        // $consultation->user->notify(new PaymentRejectedNotification($consultation, $request->rejection_reason));

        return back()->with('success', 'Pembayaran berhasil ditolak.');
    }
}
