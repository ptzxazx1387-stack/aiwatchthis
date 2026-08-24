@extends('layouts.app')

@section('title', 'داشبورد مدیریت')

@section('content')
    @php
        $queue = $stats['pending_submissions'] + $stats['pending_campaigns'] + $stats['pending_withdrawals'];
    @endphp

    <x-page-header eyebrow="Admin" title="داشبورد مدیریت"
                   lead="کارهایی که منتظر شما هستند، بالا. آمار کلی، پایین." />

    {{-- کارتابل: مهم‌ترین چیز برای مدیر --}}
    <section class="mb-24" data-reveal>
        @if($queue > 0)
            <div class="card card--pad-24">
                <div class="row between wrap" style="gap:16px">
                    <div>
                        <span class="eyebrow">Needs you</span>
                        <h2 class="h2 mt-8">{{ \App\Support\Fmt::num($queue) }} مورد در انتظار بررسی</h2>
                    </div>
                </div>

                <div class="grid grid-3 keep mt-24" style="gap:12px">
                    @foreach([
                        ['ویو در انتظار تأیید', $stats['pending_submissions'], route('admin.submissions.index'), 'check'],
                        ['کمپین در انتظار تأیید', $stats['pending_campaigns'], route('admin.campaigns.index', ['status' => 'pending']), 'megaphone'],
                        ['درخواست برداشت', $stats['pending_withdrawals'], route('admin.withdrawals.index', ['status' => 'pending']), 'banknote'],
                    ] as [$label, $count, $url, $icon])
                        <a href="{{ $url }}" class="panel row between" style="text-decoration:none">
                            <span class="stack-4">
                                <span class="micro">{{ $label }}</span>
                                <span class="h3 figure {{ $count > 0 ? 'tone-amber' : 'dim' }}">{{ \App\Support\Fmt::num($count) }}</span>
                            </span>
                            <x-icon :name="$icon" :size="18" class="dim" />
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card">
                <x-empty-state icon="check-circle" title="کارتابل خالی است" tight>
                    هیچ ویو، کمپین یا درخواست برداشتی در انتظار بررسی نیست.
                </x-empty-state>
            </div>
        @endif
    </section>

    <section class="section">
        <span class="eyebrow">Overview</span>
        <h2 class="h2 mb-16" style="margin-block-start:8px">آمار کلی سامانه</h2>

        <div class="grid grid-4 keep">
            <x-stat-card label="کل کاربران" :value="$stats['total_users']" icon="users"
                         :href="route('admin.users.index')" />
            <x-stat-card label="تبلیغ‌دهندگان" :value="$stats['total_advertisers']" icon="megaphone"
                         :href="route('admin.users.index', ['role' => 'advertiser'])" />
            <x-stat-card label="سفیران" :value="$stats['total_ambassadors']" icon="star"
                         :href="route('admin.ambassadors.index')" />
            <x-stat-card label="کمپین‌های فعال" :value="$stats['active_campaigns']" icon="trend"
                         :href="route('admin.campaigns.index', ['status' => 'active'])" />
            <x-stat-card label="ویوهای تأییدشده" :value="$stats['total_approved_views']" icon="eye"
                         class="span-2" foot="مجموع ویوهای تحویل‌شده در کل سامانه" />
        </div>
    </section>

    <section class="section grid grid-2 grid-24">
        <div class="card card--flush" data-reveal>
            <x-section-head title="آخرین ثبت‌ویوها" :href="route('admin.submissions.index')" />
            <div class="list">
                @forelse($recentSubmissions as $s)
                    <div class="list__row">
                        <x-avatar :name="$s->ambassador->name" size="sm" tone="mist" />
                        <div class="grow stack-4">
                            <span class="strong truncate">{{ $s->ambassador->name }}</span>
                            <span class="micro truncate">
                                {{ $s->campaign->title }} · <span class="figure">{{ \App\Support\Fmt::num($s->views_count) }}</span> ویو
                            </span>
                        </div>
                        <x-badge :tone="$s->status->tone()">{{ $s->status->label() }}</x-badge>
                    </div>
                @empty
                    <x-empty-state icon="upload" title="ثبت‌ویویی وجود ندارد" tight />
                @endforelse
            </div>
        </div>

        <div class="card card--flush" data-reveal>
            <x-section-head title="آخرین کمپین‌ها" :href="route('admin.campaigns.index')" />
            <div class="list">
                @forelse($recentCampaigns as $c)
                    <a href="{{ route('admin.campaigns.show', $c) }}" class="list__row">
                        <div class="grow stack-4">
                            <span class="strong truncate">{{ $c->title }}</span>
                            <span class="micro truncate">
                                {{ $c->advertiser->name }} · ظرفیت <span class="figure">{{ \App\Support\Fmt::num($c->capacity) }}</span>
                            </span>
                        </div>
                        <x-badge :tone="$c->status->tone()">{{ $c->status->label() }}</x-badge>
                    </a>
                @empty
                    <x-empty-state icon="megaphone" title="کمپینی وجود ندارد" tight />
                @endforelse
            </div>
        </div>
    </section>
@endsection
