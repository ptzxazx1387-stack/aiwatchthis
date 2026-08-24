<!DOCTYPE html>
<html lang="fa" dir="rtl" class="no-js">
<head>
    <script>document.documentElement.classList.remove('no-js');</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#050510">
    <title>@yield('title', 'ورود') — {{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fontsource-variable/space-grotesk@5.3.0/index.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cosmos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>
<body>

{{-- جوّ کهکشانی — لایه‌های پشت همه‌چیز --}}
<div class="nebula" aria-hidden="true"></div>
<canvas id="starfield" class="starfield" aria-hidden="true"></canvas>
<div class="noise" aria-hidden="true"></div>

<div class="auth">

    {{-- ————————————————————————————— سمت روایت --}}
    <aside class="auth__aside">
        <div class="orb orb--indigo orb--md" style="top:-14%;inset-inline-end:-10%" aria-hidden="true"></div>
        <div class="orb orb--violet orb--sm orb--slow" style="bottom:6%;inset-inline-start:8%" aria-hidden="true"></div>

        <a href="{{ url('/') }}" class="row" style="align-self:flex-start;position:relative">
            <span class="mark" dir="ltr">IC</span>
            <span class="h4" style="color:#f1f5f9">{{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</span>
        </a>

        <div class="auth__quote" style="max-width:34ch">
            <span class="eyebrow">Instagram Story Campaigns</span>
            <p class="display" style="color:#f1f5f9;font-size:clamp(34px, 4.4vw, 52px);margin-block-start:16px" dir="ltr">
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

<script src="{{ asset('js/cosmos.js') }}" defer></script>
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
