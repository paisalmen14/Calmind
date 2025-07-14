<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Pastikan baris ini ada untuk menggunakan model User

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Mencoba mengotentikasi pengguna dengan email dan password yang diberikan
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Jika otentikasi gagal, catat upaya login yang gagal
            RateLimiter::hit($this->throttleKey());

            // Lempar pengecualian validasi dengan pesan kesalahan
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // --- LOGIKA PENGECEKAN STATUS PSIKOLOG DITAMBAHKAN DI SINI ---
        $user = Auth::user(); // Dapatkan objek pengguna yang baru saja diotentikasi

        // Jika peran pengguna adalah 'psikolog' dan statusnya bukan 'approved'
        if ($user->role === 'psikolog' && $user->psychologist_status !== 'approved') {
            $status = $user->psychologist_status; // Ambil status saat ini ('pending' atau 'rejected')
            
            // Logout pengguna yang mencoba masuk karena akun mereka belum aktif
            Auth::logout();
            $this->session()->invalidate(); // Hapus sesi
            $this->session()->regenerateToken(); // Regenerasi token CSRF

            // Siapkan pesan kesalahan berdasarkan status psikolog
            $message = 'Akun Anda belum dapat digunakan. Status saat ini: Menunggu Persetujuan Admin.';
            if ($status === 'rejected') {
                $message = 'Login gagal. Pendaftaran Anda sebagai psikolog telah ditolak oleh Admin.';
            }

            // Lempar pengecualian validasi dengan pesan yang sesuai
            throw ValidationException::withMessages([
                'email' => $message,
            ]);
        }
        // --- AKHIR LOGIKA PENGECEKAN STATUS PSIKOLOG ---

        // Jika otentikasi berhasil dan tidak ada masalah status, hapus batasan laju
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        // Periksa apakah upaya login melebihi batas yang diizinkan (5 kali dalam 1 menit)
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return; // Jika tidak melebihi batas, lanjutkan
        }

        // Jika melebihi batas, picu event Lockout
        event(new Lockout($this));

        // Dapatkan sisa waktu (dalam detik) hingga pengguna dapat mencoba lagi
        $seconds = RateLimiter::availableIn($this->throttleKey());

        // Lempar pengecualian validasi dengan pesan batasan laju
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        // Buat kunci unik untuk batasan laju berdasarkan email dan alamat IP pengguna
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
