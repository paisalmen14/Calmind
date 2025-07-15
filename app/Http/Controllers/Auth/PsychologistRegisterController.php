<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PsychologistProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN baris ini
use Illuminate\Auth\Events\Registered; // <-- TAMBAHKAN baris ini

class PsychologistRegisterController extends Controller
{
    public function create()
    {
        return view('auth.register-psychologist');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'ktp_number' => ['required', 'string', 'digits:16'],
            'university' => ['required', 'string', 'max:255'],
            'graduation_year' => ['required', 'digits:4', 'integer', 'min:1900', 'max:'.(date('Y'))],
            'certificate' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            'ktp_image' => ['required', 'file', 'mimes:jpg,png,jpeg', 'max:2048'],
        ]);

        $certificatePath = $request->file('certificate')->store('certificates', 'public');
        $ktpPath = $request->file('ktp_image')->store('ktp_images', 'public');

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'psikolog',
            'psychologist_status' => 'pending',
        ]);

        $user->psychologistProfile()->create([
            'ktp_number' => $request->ktp_number,
            'university' => $request->university,
            'graduation_year' => $request->graduation_year,
            'certificate_path' => $certificatePath,
            'ktp_path' => $ktpPath,
        ]);
        
        // ===============================================
        // UBAH BAGIAN DI BAWAH INI
        // ===============================================

        // 1. Kirim event bahwa user telah terdaftar
        event(new Registered($user));
        
        // 2. Login-kan pengguna secara otomatis
        // Auth::login($user);

        // 3. Alihkan ke halaman login dengan pesan status
        // Karena psikolog perlu approval, kita tetap arahkan ke login dengan pesan.
        // Jika Anda ingin psikolog langsung masuk, hapus baris di bawah dan uncomment baris Auth::login dan redirect ke dashboard.
        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Akun Anda akan segera diverifikasi oleh Admin sebelum dapat digunakan.');
    }
}