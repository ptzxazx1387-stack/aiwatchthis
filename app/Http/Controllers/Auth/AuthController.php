<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * نمایش فرم ورود
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * پردازش ورود
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'ایمیل یا رمز عبور اشتباه است.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'حساب کاربری شما غیرفعال شده است.']);
        }

        ActivityLog::record($user->id, 'auth.login', 'ورود به سامانه');

        return redirect()->intended(route($user->dashboardRoute()));
    }

    /**
     * نمایش فرم ثبت‌نام
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * پردازش ثبت‌نام (تبلیغ‌دهنده یا سفیر)
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:advertiser,ambassador'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::from($data['role']),
            'status' => 'active',
        ]);

        // ساخت کیف پول برای همه کاربران
        Wallet::firstOrCreate(['user_id' => $user->id]);

        ActivityLog::record($user->id, 'auth.register', 'ثبت‌نام در سامانه');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route($user->dashboardRoute()));
    }

    /**
     * خروج از حساب کاربری
     */
    public function logout(Request $request): RedirectResponse
    {
        ActivityLog::record(Auth::id(), 'auth.logout', 'خروج از سامانه');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
