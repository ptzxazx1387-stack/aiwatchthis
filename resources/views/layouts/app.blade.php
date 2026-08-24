<!DOCTYPE html>
<html lang="fa" dir="rtl" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#f7f7f5">
    <title>@yield('title', 'داشبورد') — {{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>
<body>
@php
    $user = auth()->user();
    $navGroups = \App\Support\Navigation::groups($user);
    $flatNav = collect($navGroups)->flatMap(fn ($g) => $g['items']);
    $dockItems = $flatNav->where('dock', true)->take(4);
    $unread = $user->unreadNotifications()->count();
    $wallet = $user->wallet;
@endphp

<a href="#content" class="skip-link">پرش به محتوای اصلی</a>

<div class="shell">
    <header class="topbar" data-topbar>
        <div class="topbar__inner">
            <a href="{{ route('dashboard') }}" class="brand" aria-label="داشبورد">
                <span class="mark" dir="ltr">IW</span>
                <span class="stack-4" style="gap:2px">
                    <span class="brand__title">{{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</span>
                    <span class="brand__sub">Campaign Desk</span>
                </span>
            </a>

            <div class="topbar__navwrap">
                <button type="button" class="searchbtn" data-palette-open>
                    <x-icon name="search" :size="16" />
                    <span class="grow" style="text-align:start">جست‌وجو یا رفتن به…</span>
                    <span class="kbd no-mobile" dir="ltr">Ctrl K</span>
                </button>

                <nav class="mainnav" aria-label="پیمایش اصلی">
                @foreach($navGroups as $group)
                    @if(count($group['items']) === 1)
                        @php($item = $group['items'][0])
                        <a href="{{ route($item['route']) }}" @if(request()->routeIs(...explode('|', $item['match']))) aria-current="page" @endif>
                            <x-icon :name="$item['icon']" :size="15" />
                            <span>{{ $item['label'] }}</span>
                            @if(!empty($item['count']))<span class="mainnav__count figure">{{ \App\Support\Fmt::num($item['count']) }}</span>@endif
                        </a>
                    @else
                        <div class="mainnav__group" data-nav-group>
                            <button type="button" class="mainnav__btn" aria-haspopup="true" aria-expanded="false">
                                <span>{{ $group['label'] }}</span>
                                <x-icon name="down" :size="14" />
                            </button>
                            <div class="mainnav__menu" role="menu">
                                @foreach($group['items'] as $item)
                                    <a href="{{ route($item['route']) }}" role="menuitem" @if(request()->routeIs(...explode('|', $item['match']))) aria-current="page" @endif>
                                        <x-icon :name="$item['icon']" :size="15" />
                                        <span>{{ $item['label'] }}</span>
                                        @if(!empty($item['count']))<span class="mainnav__count figure">{{ \App\Support\Fmt::num($item['count']) }}</span>@endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
                </nav>
            </div>

            <div class="topbar__actions">
                <a href="{{ route('notifications.index') }}" class="iconbtn no-mobile" aria-label="اعلان‌ها">
                    <x-icon name="bell" :size="19" />
                    @if($unread)<span class="iconbtn__dot"></span>@endif
                </a>

                <button type="button" class="iconbtn menu-toggle" data-menu-open aria-label="باز کردن منو" aria-expanded="false">
                    <x-icon name="menu" :size="21" />
                </button>
            </div>
        </div>
        <div class="topbar__progress" data-scroll-progress aria-hidden="true"></div>
    </header>

    <div class="mobilemenu" data-mobile-menu hidden>
        <div class="mobilemenu__panel" role="dialog" aria-modal="true" aria-label="منو">
            <div class="row between mb-16">
                <span class="eyebrow">Menu</span>
                <button type="button" class="iconbtn" data-menu-close aria-label="بستن منو"><x-icon name="x" :size="18" /></button>
            </div>
            <a href="{{ route('wallet.index') }}" class="row between" style="padding:14px 12px;background:#fff;border-radius:16px;box-shadow:var(--lift-0), 0 0 0 1px var(--hair)">
                <span class="stack-4">
                    <span class="micro">موجودی کیف پول</span>
                    <span class="h4 figure">{{ \App\Support\Fmt::money($wallet?->balance ?? 0) }}</span>
                </span>
                <x-icon name="wallet" :size="20" class="dim" />
            </a>

            @foreach($navGroups as $group)
                <div class="mobilemenu__section">
                    <p class="mobilemenu__label">{{ $group['label'] }}</p>
                    <div class="stack-4">
                        @foreach($group['items'] as $item)
                            <a href="{{ route($item['route']) }}" @if(request()->routeIs(...explode('|', $item['match']))) aria-current="page" @endif>
                                <x-icon :name="$item['icon']" :size="17" />
                                <span>{{ $item['label'] }}</span>
                                @if(!empty($item['count']))<span class="push badge badge--rose">{{ \App\Support\Fmt::num($item['count']) }}</span>@endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mobilemenu__section">
                <div class="stack-4">
                    <div class="row" style="padding:12px">
                        <x-avatar :name="$user->name" />
                        <span class="stack-4 grow">
                            <span class="strong truncate">{{ $user->name }}</span>
                            <span class="micro">{{ $user->role->label() }}</span>
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" data-confirm="از حساب خود خارج می‌شوید؟" data-confirm-title="خروج" data-confirm-ok="خروج">
                        @csrf
                        <button type="submit" class="btn btn--ghost btn--block">
                            <x-icon name="logout" :size="16" />
                            خروج از حساب
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <main class="page" id="content">
        @yield('content')

        <footer class="page-foot">
            <span>© {{ \App\Support\Fmt::num(now()->year) }} {{ \App\Models\Setting::get('site_name', 'سامانه کمپین') }}</span>
            <span>طراحی مینیمال، راست‌چین و فارسی‌محور</span>
        </footer>
    </main>
</div>

<nav class="dock" aria-label="پیمایش سریع">
    <div class="dock__row">
        @foreach($dockItems as $item)
            <a href="{{ route($item['route']) }}" @if(request()->routeIs(...explode('|', $item['match']))) aria-current="page" @endif>
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
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
