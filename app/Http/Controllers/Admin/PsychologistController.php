<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PsychologistController extends Controller
{
    /**
     * Menampilkan daftar psikolog yang menunggu persetujuan.
     */
    public function index()
    {
        // TODO: Buat view di resources/views/admin/psychologists/index.blade.php
        $psychologists = User::where('role', 'psikolog')
                             ->where('psychologist_status', 'pending')
                             ->with('psychologistProfile')
                             ->paginate(10);
        return view('admin.psychologists.index', compact('psychologists'));
    }

    /**
     * Menyetujui pendaftaran psikolog.
     */
    public function approve(User $psychologist)
    {
        $psychologist->psychologist_status = 'approved';
        $psychologist->save();
        
        // Opsional: Kirim notifikasi ke psikolog bahwa akunnya sudah disetujui
        // $psychologist->notify(new YourAccountHasBeenApproved());

        return back()->with('success', 'Psikolog berhasil disetujui.');
    }

    /**
     * Menolak pendaftaran psikolog.
     */
    public function reject(User $psychologist)
    {
        $psychologist->psychologist_status = 'rejected';
        $psychologist->save();

        // Opsional: Kirim notifikasi ke psikolog bahwa akunnya ditolak
        // $psychologist->notify(new YourAccountHasBeenRejected());

        return back()->with('success', 'Psikolog berhasil ditolak.');
    }
}
