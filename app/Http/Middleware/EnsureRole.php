<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * محدودکردن دسترسی بر اساس نقش کاربر.
     * استفاده: ->middleware('role:admin,advertiser')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($user->role->value, $roles, true)) {
            abort(403, 'شما به این بخش دسترسی ندارید.');
        }

        return $next($request);
    }
}
