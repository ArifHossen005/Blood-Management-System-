<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, __('ui.messages.no_permission'));
        }

        if ($user->isMainAdmin()) {
            return $next($request);
        }

        if (!$user->isSubAdmin()) {
            abort(403, __('ui.messages.no_permission'));
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        abort(403, __('ui.messages.no_permission_for_this'));
    }
}
