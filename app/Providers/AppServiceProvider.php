<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Story;
use App\Models\User;
use App\Models\Comment;
use App\Models\Consultation;
use Carbon\Carbon; // Import Carbon untuk manipulasi waktu

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
            // Pastikan pengguna adalah pasien atau psikolog dalam konsultasi ini
            if ($user->id !== $consultation->user_id && $user->id !== $consultation->psychologist_id) {
                return false;
            }

            // Hanya izinkan akses live chat untuk konsultasi yang berstatus 'confirmed'
            if ($consultation->status !== 'confirmed') {
                return false;
            }

            // Definisikan jendela waktu live chat:
            // Dimulai 5 menit sebelum waktu yang diminta
            $chatWindowStart = (clone $consultation->requested_start_time)->subMinutes(5);
            // Berakhir 15 menit setelah durasi konsultasi selesai
            $chatWindowEnd = (clone $consultation->requested_start_time)->addMinutes($consultation->duration_minutes)->addMinutes(15);

            // Periksa apakah waktu saat ini berada dalam jendela ini
            return Carbon::now()->between($chatWindowStart, $chatWindowEnd);
        });

        /**
         * Gate untuk melihat riwayat chat konsultasi.
         * Memungkinkan melihat riwayat untuk konsultasi yang sudah selesai, ditolak, dibatalkan,
         * sedang menunggu verifikasi pembayaran, ATAU sesi yang sudah dikonfirmasi dan waktu mulainya sudah berlalu.
         */
        Gate::define('view-chat-history', function (User $user, Consultation $consultation) {
            // Pastikan pengguna adalah pasien atau psikolog dalam konsultasi ini
            if ($user->id !== $consultation->user_id && $user->id !== $consultation->psychologist_id) {
                return false;
            }

            // Izinkan melihat riwayat jika statusnya adalah salah satu dari berikut:
            // - 'completed', 'payment_rejected', 'cancelled', 'pending_verification'
            if (in_array($consultation->status, ['completed', 'payment_rejected', 'cancelled', 'pending_verification'])) {
                return true;
            }

            // ATAU jika statusnya 'confirmed' DAN waktu mulai sesi sudah lewat
            if ($consultation->status === 'confirmed' && Carbon::now()->greaterThanOrEqualTo($consultation->requested_start_time)) {
                return true;
            }

            return false; 
        });
    }
}
