<!DOCTYPE html>
<html lang="fa" dir="rtl" class="no-js">
<head>
    <script>document.documentElement.classList.remove('no-js');</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#f7f7f5">
    <title>@yield('title', 'ورود') — {{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fontsource/instrument-serif@5.0.0/index.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>
<body>

<div class="auth">

    {{-- ————————————————————————————— سمت روایت --}}
    <aside class="auth__aside">
        <a href="{{ url('/') }}" class="row" style="align-self:flex-start">
            <span class="mark" dir="ltr" style="background:#fff;color:var(--ink)">IC</span>
            <span class="h4" style="color:#fff">{{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</span>
        </a>

        <div class="auth__quote" style="max-width:34ch">
            <span class="eyebrow">Instagram Story Campaigns</span>
            <p class="display" style="color:#fff;font-size:clamp(34px, 4.4vw, 52px);margin-block-start:16px" dir="ltr">
                Reach, <em class="display--italic">measured</em>.
            </p>
            <p class="body" style="color:rgba(255,255,255,.62);margin-block-start:20px">
                تبلیغ‌دهنده کمپین می‌سازد، سفیر استوری می‌گذارد، سامانه ویوها را
                می‌شمارد و درآمد را تسویه می‌کند. همین.
            </p>
        </div>

        <div class="ticker" aria-hidden="true">
            <div class="ticker__track">
                @foreach([1, 2] as $pass)
                    @foreach(['کمپین', 'سفیر', 'استوری', 'ویو', 'تسویه', 'گزارش'] as $word)
                        <span class="eyebrow" style="color:rgba(255,255,255,.25)">{{ $word }}</span>
                    @endforeach
                @endforeach
            </div>
        </div>
    </aside>

    {{-- ————————————————————————————— سمت فرم --}}
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
