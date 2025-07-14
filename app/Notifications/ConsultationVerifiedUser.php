<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Consultation;

class ConsultationVerifiedUser extends Notification
{
    use Queueable;

    public function __construct(public Consultation $consultation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('consultations.history');
        return (new MailMessage)
                    ->subject('Jadwal Konsultasi Anda Telah Dikonfirmasi!')
                    ->line('Pembayaran Anda telah berhasil diverifikasi.')
                    ->line('Jadwal konsultasi Anda dengan ' . $this->consultation->psychologist->name . ' telah dikonfirmasi.')
                    ->line('Tanggal: ' . $this->consultation->requested_start_time->format('d F Y, H:i T'))
                    ->action('Lihat Detail Konsultasi', $url)
                    ->line('Anda dapat memulai chat pada waktu yang telah ditentukan.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Jadwal konsultasimu dengan ' . $this->consultation->psychologist->name . ' telah dikonfirmasi!',
            'link' => route('consultations.history'),
            'consultation_id' => $this->consultation->id,
        ];
    }
}