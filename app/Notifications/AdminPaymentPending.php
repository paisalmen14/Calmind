<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Consultation;

class AdminPaymentPending extends Notification
{
    use Queueable;

    public function __construct(public Consultation $consultation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // Kirim via database dan email
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('admin.consultation.verifications.index'); // Arahkan ke halaman verifikasi
        return (new MailMessage)
                    ->subject('Pembayaran Konsultasi Perlu Verifikasi')
                    ->line('Pembayaran baru untuk sesi konsultasi telah diterima dan perlu verifikasi Anda.')
                    ->line('User: ' . $this->consultation->user->name)
                    ->line('Psikolog: ' . $this->consultation->psychologist->name)
                    ->action('Lihat Detail Pembayaran', $url)
                    ->line('Silakan segera lakukan verifikasi.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Pembayaran dari ' . $this->consultation->user->name . ' untuk konsultasi dengan ' . $this->consultation->psychologist->name . ' perlu diverifikasi.',
            'link' => route('admin.consultation.verifications.index'),
            'consultation_id' => $this->consultation->id,
        ];
    }
}