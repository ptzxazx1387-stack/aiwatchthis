@extends('layouts.app')

@section('title', 'داشبورد تبلیغ‌دهنده')

@section('content')
    <x-page-header eyebrow="Advertiser" title="سلام {{ auth()->user()->name }}"
                   lead="وضعیت کمپین‌های شما در یک نگاه.">
        <x-slot:actions>
            <a href="{{ route('advertiser.campaigns.create') }}" class="btn">
                <x-icon name="plus" :size="15" />
                کمپین جدید
            </a>
        </x-slot:actions>
    </x-page-header>

    @if($stats['pending_campaigns'] > 0)
        <div class="note note--amber mb-24" data-reveal>
            <x-icon name="clock" :size="16" />
            <p class="grow">
                {{ \App\Support\Fmt::num($stats['pending_campaigns']) }} کمپین شما در انتظار تأیید مدیر است.
                تا تأیید نشود به سفیران تخصیص داده نمی‌شود.
            </p>
        </div>
    @endif

    <div class="ledger mb-24" data-reveal>
        <div>
            <span class="eyebrow">Balance</span>
            <p class="ledger__value">
                {{ \App\Support\Fmt::money($stats['wallet_balance']) }}<span class="ledger__unit"> تومان</span>
            </p>
            <p class="ledger__side">اعتبار قابل استفاده برای کمپین‌ها</p>
        </div>
        <a href="{{ route('wallet.index') }}" class="btn">
            <x-icon name="wallet" :size="15" />
            کیف پول
        </a>
    </div>

    <div class="grid grid-4 keep mb-24">
        <x-stat-card label="کل کمپین‌ها" :value="$stats['total_campaigns']" icon="megaphone"
                     :href="route('advertiser.campaigns.index')" />
        <x-stat-card label="در حال اجرا" :value="$stats['active_campaigns']" icon="trend"
                     :href="route('advertiser.campaigns.index', ['status' => 'active'])" />
        <x-stat-card label="در انتظار تأیید" :value="$stats['pending_campaigns']" icon="clock" flag
                     :href="route('advertiser.campaigns.index', ['status' => 'pending'])" />
        <x-stat-card label="ویوهای تأییدشده" :value="$stats['total_approved_views']" icon="eye"
                     foot="جمع ویوهای تحویل‌شده" />
    </div>

    <section class="card card--flush" data-reveal>
        <x-section-head title="آخرین کمپین‌ها" :href="route('advertiser.campaigns.index')" />

        <div class="list">
            @forelse($recentCampaigns as $campaign)
                <a href="{{ route('advertiser.campaigns.show', $campaign) }}" class="list__row">
                    <div class="grow stack-4">
                        <span class="strong truncate">{{ $campaign->title }}</span>
                        <span class="micro">
                            {{ \App\Support\Fmt::num($campaign->capacity - $campaign->remaining_capacity) }}
                            از {{ \App\Support\Fmt::num($campaign->capacity) }} ویو تأمین شده
                        </span>
                    </div>

                    <div class="no-mobile" style="inline-size:120px">
                        <x-progress :value="$campaign->capacity - $campaign->remaining_capacity"
                                    :max="$campaign->capacity" label="پیشرفت کمپین" />
                    </div>

                    <x-badge :tone="$campaign->status->tone()">{{ $campaign->status->label() }}</x-badge>
                    <x-icon name="chevron" class="icon-forward dim" :size="14" />
                </a>
            @empty
                <x-empty-state icon="megaphone" title="هنوز کمپینی نساخته‌اید">
                    اولین کمپین‌تان را بسازید — عنوان، ظرفیت و قیمت هر ویو کافی است.
                    <span style="display:block;margin-block-start:14px">
                        <a href="{{ route('advertiser.campaigns.create') }}" class="btn btn--sm">ساخت کمپین</a>
                    </span>
                </x-empty-state>
            @endforelse
        </div>
    </section>
@endsection
