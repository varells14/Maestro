<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            if ($user->user_role_id == 1) {
                return redirect()->route('user.dashboard_user');
            } elseif ($user->user_role_id == 2) {
                return redirect()->route('admin.dashboard_admin');
            }
        }

        return $next($request);
    }
}