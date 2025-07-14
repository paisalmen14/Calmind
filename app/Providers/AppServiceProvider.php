<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; //
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider; //
use App\Models\Story; //
use App\Models\User; //
use App\Models\Comment; //
use App\Models\Consultation; //

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        Gate::define('delete-story', function (User $user, Story $story) { //
            return $user->id === $story->user_id || $user->role === 'admin'; //
        });

        Gate::define('update-story', function (User $user, Story $story) { //
            return $user->id === $story->user_id && $story->created_at->diffInMinutes(now()) < 10; //
        });

        Gate::define('delete-comment', function (User $user, Comment $comment) { //
            return $user->id === $comment->user_id || $user->role === 'admin'; //
        });

        Gate::define('access-consultation-chat', function (User $user, Consultation $consultation) { //
            if ($user->id !== $consultation->user_id && $user->id !== $consultation->psychologist_id) { //
                return false; //
            }

            if ($consultation->status !== 'confirmed') { //
                return false; //
            }

            $startTime = $consultation->requested_start_time; //
            $endTime = (clone $startTime)->addMinutes($consultation->duration_minutes); //
            $entryTime = (clone $startTime)->subMinutes(5); //
            $exitTime = (clone $endTime)->addMinutes(5); //
           
            return now()->between($entryTime, $exitTime); //
        });

        Gate::define('view-chat-history', function (User $user, Consultation $consultation) { //
            if ($user->id !== $consultation->user_id && $user->id !== $consultation->psychologist_id) { //
                return false; //
            }
            return in_array($consultation->status, ['completed', 'payment_rejected', 'cancelled']); //
        });
        
    }
}