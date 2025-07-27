<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Story;
use App\Models\User;
use App\Models\Comment;
use App\Models\Consultation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Pastikan ini ada

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate untuk menghapus cerita (oleh penulis atau admin)
        Gate::define('delete-story', function (User $user, Story $story) {
            return $user->id === $story->user_id || $user->role === 'admin';
        });

        // Gate untuk memperbarui cerita (oleh penulis dalam 10 menit pertama)
        Gate::define('update-story', function (User $user, Story $story) {
            return $user->id === $story->user_id && $story->created_at->diffInMinutes(now()) < 10;
        });

        // Gate untuk menghapus komentar (oleh penulis komentar atau admin)
        Gate::define('delete-comment', function (User $user, Comment $comment) {
            return $user->id === $comment->user_id || $user->role === 'admin';
        });

        /**
         * Gate untuk akses chat konsultasi secara "live".
         * Memungkinkan akses jika status 'confirmed' dan waktu saat ini berada
         * dalam jendela 5 menit sebelum waktu mulai hingga 15 menit setelah waktu selesai.
         */
        Gate::define('access-consultation-chat', function (User $user, Consultation $consultation) {
            // Definisikan $now di awal scope Gate ini
            $now = Carbon::now(); // PERBAIKAN: Definisi $now dipindahkan ke sini agar selalu tersedia.

            // Pastikan pengguna adalah pasien atau psikolog dalam konsultasi ini
            if ($user->id !== $consultation->user_id && $user->id !== $consultation->psychologist_id) {
                Log::info("[Chat Gate] Denied (User Not Participant) - Consultation ID: {$consultation->id}, User ID: {$user->id}");
                return false;
            }

            // Hanya izinkan akses live chat untuk konsultasi yang berstatus 'confirmed'
            if ($consultation->status !== 'confirmed') {
                Log::info("[Chat Gate] Denied (Status Not Confirmed) - Consultation ID: {$consultation->id}, Status: {$consultation->status}");
                return false;
            }

            // Definisikan jendela waktu live chat:
            // Dimulai 5 menit sebelum waktu yang diminta
            $chatWindowStart = (clone $consultation->requested_start_time)->subMinutes(5);
            // Berakhir 15 menit setelah durasi konsultasi selesai
            $chatWindowEnd = (clone $consultation->requested_start_time)->addMinutes($consultation->duration_minutes)->addMinutes(15);


            // Logging detail waktu untuk debugging
            Log::info("[Chat Gate] Live Chat Check for Consultation ID: {$consultation->id}");
            Log::info("  Requested Start Time: {$consultation->requested_start_time->format('Y-m-d H:i:s T')}");
            Log::info("  Duration: {$consultation->duration_minutes} minutes");
            Log::info("  Calculated Chat Window Start: {$chatWindowStart->format('Y-m-d H:i:s T')}");
            Log::info("  Calculated Chat Window End: {$chatWindowEnd->format('Y-m-d H:i:s T')}");
            Log::info("  Current Time (Carbon::now()): {$now->format('Y-m-d H:i:s T')}");

            $isBetween = $now->between($chatWindowStart, $chatWindowEnd);
            Log::info("  Is current time within live chat window? " . ($isBetween ? 'Yes (Access Granted)' : 'No (Access Denied)'));

            return $isBetween;
        });

        /**
         * Gate untuk melihat riwayat chat konsultasi.
         * Memungkinkan melihat riwayat untuk konsultasi yang sudah selesai, ditolak, dibatalkan,
         * sedang menunggu verifikasi pembayaran, ATAU sesi yang sudah dikonfirmasi dan waktu mulainya sudah berlalu.
         */
        Gate::define('view-chat-history', function (User $user, Consultation $consultation) {
            // Definisikan $now di awal scope Gate ini
            $now = Carbon::now(); // PERBAIKAN: Definisi $now dipindahkan ke sini agar selalu tersedia.

            // Pastikan pengguna adalah pasien atau psikolog dalam konsultasi ini
            if ($user->id !== $consultation->user_id && $user->id !== $consultation->psychologist_id) {
                Log::info("[Chat Gate] Denied (History - User Not Participant) - Consultation ID: {$consultation->id}, User ID: {$user->id}");
                return false;
            }

            // Izinkan melihat riwayat jika statusnya adalah salah satu dari berikut:
            if (in_array($consultation->status, ['completed', 'payment_rejected', 'cancelled', 'pending_verification'])) {
                Log::info("[Chat Gate] Granted (History - By Status) - Consultation ID: {$consultation->id}, Status: {$consultation->status}");
                return true;
            }

            // ATAU jika statusnya 'confirmed' DAN waktu mulai sesi sudah lewat
            if ($consultation->status === 'confirmed' && $now->greaterThanOrEqualTo($consultation->requested_start_time)) { // Menggunakan $now yang sudah didefinisikan
                Log::info("[Chat Gate] Granted (History - Confirmed & Passed) - Consultation ID: {$consultation->id}. Current time: {$now->format('Y-m-d H:i:s T')}, Start time: {$consultation->requested_start_time->format('Y-m-d H:i:s T')}");
                return true;
            }

            Log::info("[Chat Gate] Denied (History - No Criteria Met) - Consultation ID: {$consultation->id}, Status: {$consultation->status}");
            return false;
        });
    }
}