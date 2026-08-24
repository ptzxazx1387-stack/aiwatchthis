@extends('layouts.auth')

@section('title', 'ثبت‌نام')

@section('content')
    <span class="eyebrow">Create account</span>
    <h1 class="h2">ساخت حساب کاربری</h1>
    <p class="body mt-8">اول نقش‌تان را انتخاب کنید — بقیه‌ی فرم کوتاه است.</p>

    <form method="POST" action="{{ route('register') }}" class="stack-16 mt-32">
        @csrf

        {{-- نقش اول می‌آید: تعیین می‌کند بقیه‌ی مسیر چه شکلی است --}}
        <fieldset class="field">
            <legend class="label">نقش شما در سامانه <span class="req" aria-hidden="true">*</span></legend>
            <div class="grid grid-2 keep" style="gap:10px;margin-block-start:6px">
                <label class="tile">
                    <input type="radio" name="role" value="advertiser" required
                           {{ old('role') === 'advertiser' ? 'checked' : '' }}>
                    <span class="stack-4">
                        <span class="strong" style="font-size:13px">تبلیغ‌دهنده</span>
                        <span class="micro">کمپین می‌سازم و ویو می‌خرم</span>
                    </span>
                </label>

                <label class="tile">
                    <input type="radio" name="role" value="ambassador" required
                           {{ old('role') === 'ambassador' ? 'checked' : '' }}>
                    <span class="stack-4">
                        <span class="strong" style="font-size:13px">سفیر</span>
                        <span class="micro">پیج دارم و استوری می‌گذارم</span>
                    </span>
                </label>
            </div>
            @error('role')<p class="err">{{ $message }}</p>@enderror
        </fieldset>

        <x-field label="نام و نام خانوادگی" name="name" required>
            <input type="text" id="f-name" name="name" value="{{ old('name') }}"
                   class="input @error('name') input--err @enderror" required autocomplete="name">
        </x-field>

        <div class="grid grid-2 keep" style="gap:12px">
            <x-field label="ایمیل" name="email" required>
                <input type="email" id="f-email" name="email" value="{{ old('email') }}"
                       class="input ltr @error('email') input--err @enderror" required autocomplete="email">
            </x-field>

            <x-field label="شماره موبایل" name="phone" required>
                <input type="tel" id="f-phone" name="phone" value="{{ old('phone') }}" dir="ltr"
                       class="input ltr @error('phone') input--err @enderror"
                       required autocomplete="tel" placeholder="09xxxxxxxxx" inputmode="numeric">
            </x-field>
        </div>

        <div class="grid grid-2 keep" style="gap:12px">
            <x-field label="رمز عبور" name="password" required>
                <input type="password" id="f-password" name="password"
                       class="input @error('password') input--err @enderror" required autocomplete="new-password">
            </x-field>

            <x-field label="تکرار رمز عبور" name="password_confirmation" required>
                <input type="password" id="f-password_confirmation" name="password_confirmation"
                       class="input" required autocomplete="new-password">
            </x-field>
        </div>

        <button type="submit" class="btn btn--block">ساخت حساب</button>
    </form>

    <p class="meta center mt-24">
        حساب دارید؟ <a href="{{ route('login') }}" class="strong" style="text-decoration:underline;text-underline-offset:3px">وارد شوید</a>
    </p>
@endsection
