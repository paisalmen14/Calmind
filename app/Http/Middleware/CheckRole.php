<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole //
{
    public function handle(Request $request, Closure $next, ...$roles): Response //
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) { //
            abort(403, 'ANDA TIDAK PUNYA AKSES.'); //
        }

        return $next($request); //
    }
}