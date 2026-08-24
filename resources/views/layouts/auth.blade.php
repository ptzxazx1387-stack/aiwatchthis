<!DOCTYPE html>
<html lang="fa" dir="rtl" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#f7f7f5">
    <title>@yield('title', 'ورود') — {{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>
<body>
<div class="auth">
    <aside class="auth__aside">
        <a href="{{ url('/') }}" class="auth__brand">
            <span class="mark" dir="ltr">IW</span>
            <span class="stack-4" style="gap:2px">
                <span class="brand__title" style="color:#fff">{{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</span>
                <span class="brand__sub" style="color:rgba(255,255,255,.45)">Instagram Story Campaigns</span>
            </span>
        </a>

        <div class="auth__quote">
            <span class="eyebrow">Editorial Workspace</span>
            <p class="display" dir="ltr">Reach, <em class="display--italic">measured</em>.</p>
            <p class="body">تبلیغ‌دهنده کمپین می‌سازد، سفیر استوری می‌گذارد، سامانه ویوها را می‌شمارد و درآمد را تسویه می‌کند؛ بدون پیچیدگی.</p>

            <div class="auth__points mt-24">
                <div class="auth__point"><x-icon name="check-circle" :size="17" /><span>دسترسی سریع به کارتابل، کمپین‌ها، ویوها و تسویه‌ها</span></div>
                <div class="auth__point"><x-icon name="chart" :size="17" /><span>گزارش شفاف از ظرفیت، درآمد و وضعیت تأییدها</span></div>
                <div class="auth__point"><x-icon name="shield" :size="17" /><span>فرم‌های کوتاه، وضعیت‌های واضح و مسیرهای قابل‌فهم</span></div>
            </div>
        </div>

        <div class="ticker" aria-hidden="true">
            <div class="ticker__track">
                @foreach([1,2] as $pass)
                    @foreach(['Campaign', 'Ambassador', 'Story', 'Views', 'Wallet', 'Report'] as $word)
                        <span class="eyebrow">{{ $word }}</span>
                    @endforeach
                @endforeach
            </div>
        </div>
    </aside>

    <main class="auth__form">
        <div class="auth__form-inner">
            @yield('content')
            @hasSection('footer')
                <p class="micro center mt-24">@yield('footer')</p>
            @endif
        </div>
    </main>
</div>

@include('partials.flash')
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
