<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('user_name')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}