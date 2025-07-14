<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Comment;
use App\Models\Story;
use App\Models\User;

class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(public Story $story, public User $commenter) {}

    public function via(object $notifiable): array
    {
        return ['database']; // Kita akan simpan notifikasi ke database
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "<strong>{$this->commenter->name}</strong> mengomentari cerita Anda: \"{$this->story->content}\"",
            'link' => route('stories.show', $this->story->id),
            'commenter_name' => $this->commenter->name,
        ];
    }
}
