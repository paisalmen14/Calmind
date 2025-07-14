<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkNotificationAsRead
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('notif_id') && Auth::check()) {
            $notification = Auth::user()
                                ->notifications()
                                ->where('id', $request->notif_id)
                                ->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        return $next($request);
    }
}