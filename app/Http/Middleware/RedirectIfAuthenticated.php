<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect($this->dashboardFor(Auth::guard($guard)->user()));
            }
        }

        return $next($request);
    }

    protected function dashboardFor($user): string
    {
        return match ($user->role) {
            'admin', 'sub_admin' => route('admin.dashboard'),
            default              => route('donor.dashboard'),
        };
    }
}
