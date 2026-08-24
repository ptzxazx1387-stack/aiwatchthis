@extends('layouts.auth')

@section('title', 'ورود')

@section('content')
    <span class="eyebrow">Sign in</span>
    <h1 class="h2">ورود به حساب</h1>
    <p class="body mt-8">برای ادامه، ایمیل و رمز عبور خود را وارد کنید.</p>

    <form method="POST" action="{{ route('login') }}" class="stack-16 mt-32">
        @csrf

        <x-field label="ایمیل" name="email" required>
            <input type="email" id="f-email" name="email" value="{{ old('email') }}"
                   class="input ltr @error('email') input--err @enderror"
                   required autofocus autocomplete="email" placeholder="you@example.com">
        </x-field>

        <x-field label="رمز عبور" name="password" required>
            <input type="password" id="f-password" name="password"
                   class="input @error('password') input--err @enderror"
                   required autocomplete="current-password">
        </x-field>

        <label class="check">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            <span>مرا به خاطر بسپار</span>
        </label>

        <button type="submit" class="btn btn--block">ورود</button>
    </form>

    <p class="meta center mt-24">
        حساب ندارید؟ <a href="{{ route('register') }}" class="strong" style="text-decoration:underline;text-underline-offset:3px">ثبت‌نام کنید</a>
    </p>

    <div class="panel mt-24">
        <p class="micro row-6">
            <x-icon name="info" :size="13" />
            <span>ورود آزمایشی مدیر — <span class="ltr strong" style="display:inline-block">admin@example.com</span> / <span class="ltr strong" style="display:inline-block">password</span></span>
        </p>
    </div>
@endsection

@section('footer', 'سامانه مدیریت کمپین‌های تبلیغات استوری اینستاگرام')
