<!DOCTYPE html>
<html lang="fa" dir="rtl" class="no-js">
<head>
    <script>document.documentElement.classList.remove('no-js');</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#050510">
    <title>@yield('title', 'داشبورد') — {{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</title>

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

@php
    $user = auth()->user();
    $navGroups = \App\Support\Navigation::groups($user);
    $dockItems = collect($navGroups)->flatMap(fn ($g) => $g['items'])->where('dock', true)->take(4);
    $unread = $user->unreadNotifications()->count();
@endphp

<a href="#content" class="skip-link">پرش به محتوای اصلی</a>

<div class="shell">

    {{-- ————————————————————————————— ریل کناری --}}
    <div class="rail__scrim" data-rail-scrim aria-hidden="true"></div>

    <aside class="rail" id="rail" aria-label="پیمایش اصلی">
        <div class="rail__head">
            <a href="{{ route('dashboard') }}" class="row" aria-label="خانه">
                <span class="mark" dir="ltr">IC</span>
                <span class="stack">
                    <span class="h4" style="line-height:1.3">{{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</span>
                    <span class="tiny">استوری اینستاگرام</span>
                </span>
            </a>
            <button type="button" class="iconbtn push only-mobile" data-rail-toggle aria-label="بستن منو" aria-controls="rail" aria-expanded="true">
                <x-icon name="x" />
            </button>
        </div>

        <nav class="rail__nav no-bar">
            @foreach($navGroups as $group)
                <p class="rail__group">{{ $group['label'] }}</p>
                @foreach($group['items'] as $item)
                    <x-nav-link
                        :href="route($item['route'])"
                        :active="request()->routeIs(...explode('|', $item['match']))"
                        :icon="$item['icon']"
                        :count="$item['count'] ?? null">{{ $item['label'] }}</x-nav-link>
                @endforeach
            @endforeach
        </nav>

        <div class="rail__foot">
            <div class="whoami">
                <x-avatar :name="$user->name" />
                <span class="stack grow" style="min-width:0">
                    <span class="strong truncate" style="font-size:13px">{{ $user->name }}</span>
                    <span class="tiny">{{ $user->role->label() }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}"
                      data-confirm="از حساب خود خارج می‌شوید و باید دوباره وارد شوید."
                      data-confirm-title="خروج از حساب"
                      data-confirm-ok="خروج">
                    @csrf
                    <button type="submit" class="iconbtn" aria-label="خروج از حساب">
                        <x-icon name="logout" />
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ————————————————————————————— ستون اصلی --}}
    <div class="main">

        <header class="topbar" data-topbar>
            <div class="topbar__inner">
                <button type="button" class="iconbtn only-mobile" data-rail-toggle aria-label="باز کردن منو" aria-controls="rail" aria-expanded="false">
                    <x-icon name="menu" :size="20" />
                </button>

                <button type="button" class="searchbtn grow" data-palette-open>
                    <x-icon name="search" :size="15" />
                    <span class="grow" style="text-align:start">جست‌وجو یا رفتن به…</span>
                    <span class="kbd no-mobile" dir="ltr">Ctrl K</span>
                </button>

                <a href="{{ route('notifications.index') }}" class="iconbtn"
                   aria-label="{{ $unread ? 'اعلان‌ها — '.\App\Support\Fmt::num($unread).' خوانده‌نشده' : 'اعلان‌ها' }}">
                    <x-icon name="bell" :size="18" />
                    @if($unread)<span class="iconbtn__dot"></span>@endif
                </a>

                <a href="{{ route('wallet.index') }}" class="iconbtn no-mobile" aria-label="کیف پول">
                    <x-icon name="wallet" :size="18" />
                </a>
            </div>
            <div class="topbar__progress" data-scroll-progress aria-hidden="true"></div>
        </header>

        <main class="page" id="content">
            @yield('content')
        </main>
    </div>
</div>

{{-- ————————————————————————————— داک پایین، فقط موبایل --}}
<nav class="dock" aria-label="پیمایش سریع">
    <div class="dock__row">
        @foreach($dockItems as $item)
            <a href="{{ route($item['route']) }}"
               @if(request()->routeIs(...explode('|', $item['match']))) aria-current="page" @endif>
                <x-icon :name="$item['icon']" :size="19" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
        <button type="button" data-palette-open aria-label="جست‌وجو">
            <x-icon name="search" :size="19" />
            <span>جست‌وجو</span>
        </button>
    </div>
</nav>

@include('partials.flash')
@include('partials.dialogs')

<script type="application/json" id="palette-data">@json(\App\Support\Navigation::paletteItems($user), JSON_UNESCAPED_UNICODE)</script>
<script src="{{ asset('js/cosmos.js') }}" defer></script>
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
