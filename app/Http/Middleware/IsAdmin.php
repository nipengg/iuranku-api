<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role == 'Admin') {
            return $next($request);
        } else {
            return $request->expectsJson() ? ResponseFormatter::error([
                'message' => 'Unauthorized',
            ], 'Authorization Failed', 401) 
            : redirect('/login');
        }
    }
}
