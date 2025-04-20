<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('auth.login.form')
                ->with('error', 'You need to login first');
        }

        if (!$request->user()->is_active) {
            Auth::logout();
            return redirect()
                ->route('auth.login.form')
                ->with('error', 'Your account has been deactived');
        }

        return $next($request);
    }
}
