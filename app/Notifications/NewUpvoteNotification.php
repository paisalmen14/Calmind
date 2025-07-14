<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Story;
use App\Models\User;

class NewUpvoteNotification extends Notification
{
    use Queueable;

    public function __construct(public Story $story, public User $voter) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "<strong>{$this->voter->name}</strong> menyukai cerita Anda: \"{$this->story->content}\"",
            'link' => route('stories.show', $this->story->id),
            'voter_name' => $this->voter->name,
        ];
    }
}