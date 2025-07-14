<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Consultation;

class ConsultationVerifiedPsychologist extends Notification
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
        // Arahkan psikolog ke halaman jadwalnya
        $url = route('psychologist.availability.index'); 
        
        return (new MailMessage)
                    ->subject('Jadwal Konsultasi Baru Telah Dikonfirmasi')
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Anda memiliki jadwal konsultasi baru yang telah dikonfirmasi.')
                    ->line('Pasien: ' . $this->consultation->user->name)
                    ->line('Jadwal: ' . $this->consultation->requested_start_time->format('l, d F Y, H:i T'))
                    ->line('Durasi: ' . $this->consultation->duration_minutes . ' menit')
                    ->action('Lihat Jadwal Anda', $url)
                    ->line('Harap bersiap pada waktu yang telah ditentukan.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Jadwal konsultasi baru dengan ' . $this->consultation->user->name . ' telah dikonfirmasi.',
            'link' => route('psychologist.availability.index'),
            'consultation_id' => $this->consultation->id,
        ];
    }
}