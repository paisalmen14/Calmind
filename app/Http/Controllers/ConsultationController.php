<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConsultationController extends Controller
{
    /**
     * Menampilkan daftar psikolog yang tersedia.
     */
    public function index()
    {
        $psychologists = User::where('role', 'psikolog')
            ->where('psychologist_status', 'approved')
            ->with('psychologistProfile')
            ->paginate(10);
        return view('consultations.index', compact('psychologists'));
    }

    /**
     * Menampilkan detail satu psikolog dan jadwalnya.
     */
    public function show(User $psychologist)
    {
        $psychologist->load('psychologistProfile', 'availabilities');
        $availableSlots = $psychologist->availabilities()
            ->where('is_booked', false)
            ->where('start_time', '>', now())
            ->get();
            
        return view('consultations.show', compact('psychologist', 'availableSlots'));
    }

    /**
     * Proses membuat reservasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'psychologist_id' => 'required|exists:users,id',
            'requested_start_time' => 'required|date',
            'duration_minutes' => 'required|integer|in:30,60,90',
        ]);

        $psychologist = User::with('psychologistProfile')->find($request->psychologist_id);
        $user = Auth::user();

        // Hitung biaya
        $pricePerHour = $psychologist->psychologistProfile->price_per_hour;
        $psychologistPrice = ($pricePerHour / 60) * $request->duration_minutes;
        $adminFee = $psychologistPrice * 0.05; // Fee 5%
        $totalPayment = $psychologistPrice + $adminFee;

        // Buat konsultasi
        $consultation = Consultation::create([
            'user_id' => $user->id,
            'psychologist_id' => $psychologist->id,
            'requested_start_time' => $request->requested_start_time,
            'duration_minutes' => $request->duration_minutes,
            'psychologist_price' => $psychologistPrice,
            'admin_fee' => $adminFee,
            'total_payment' => $totalPayment,
            'status' => 'pending_payment',
            'expires_at' => now()->addHour(),
        ]);
        
        // Redirect ke halaman pembayaran
        return redirect()->route('consultations.payment.create', $consultation);
    }

    /**
     * Menampilkan riwayat konsultasi user.
     * VERSI PERBAIKAN: Kode ini memastikan $consultations selalu berupa Paginator.
     */
    public function history()
    {
        $user = auth()->user();
        
        // Mulai dengan Query Builder dasar
        $query = Consultation::query();

        if ($user->role === 'pengguna') {
            // Muat riwayat sebagai pasien
            $query = $user->consultationsAsUser()->with('psychologist');
        } elseif ($user->role === 'psikolog') {
            // Muat riwayat sebagai psikolog
            $query = $user->consultationsAsPsychologist()->with('user');
        } else {
            // Untuk Admin atau peran lain, buat query yang hasilnya pasti kosong
            // tapi tetap bisa dipaginasi untuk menghindari error.
            $query->whereRaw('1 = 0');
        }

        // Selalu panggil paginate di akhir pada query builder
        $consultations = $query->latest()->paginate(10);

        return view('consultations.history', compact('consultations'));
    }
}