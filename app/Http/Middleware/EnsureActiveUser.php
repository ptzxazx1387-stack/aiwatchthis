<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveUser
{
    /**
     * جلوگیری از دسترسی کاربران معلق‌شده
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->status !== 'active') {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'حساب کاربری شما غیرفعال شده است. لطفاً با پشتیبانی تماس بگیرید.']);
        }

        return $next($request);
    }
}
