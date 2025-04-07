<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, String $role)
    {
        if (!Auth::check()) {
            return response()->json(["message" => "error", "error" => "You need to login first."], 401);
        }

        if (!$request->user()->getRole($role)) {
            return response()->json(["message" => "errro", "error" => "You don't have acces to this page !"], 403);
        }

        return $next($request);
    }
}
