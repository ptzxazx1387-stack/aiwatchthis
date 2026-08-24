<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AIWatchThis') }}</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <main class="page narrow" style="padding-block-start:96px">
        <div class="card stack-24" data-reveal>
            <span class="eyebrow">AIWatchThis</span>
            <h1 class="h1">سامانه مدیریت کمپین استوری اینستاگرام</h1>
            <p class="lead">برای ورود به داشبورد، ثبت کمپین، بررسی سفیران، ثبت ویو و تسویه کیف پول وارد حساب کاربری خود شوید.</p>
            <div class="row-8 wrap">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn">ورود به داشبورد</a>
                @else
                    <a href="{{ route('login') }}" class="btn">ورود</a>
                    <a href="{{ route('register') }}" class="btn btn--ghost">ثبت‌نام</a>
                @endauth
            </div>
        </div>
    </main>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
