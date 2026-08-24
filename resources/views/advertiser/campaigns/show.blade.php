@extends('layouts.app')

@section('title', $campaign->title)

@section('content')
    @php
        $delivered = $campaign->capacity - $campaign->remaining_capacity;
        $assignments = $campaign->assignments;
    @endphp

    <x-page-header eyebrow="Campaign" :title="$campaign->title"
                   :back="route('advertiser.campaigns.index')" back-label="بازگشت به کمپین‌ها">
        <x-slot:actions>
            <x-badge :tone="$campaign->status->tone()">{{ $campaign->status->label() }}</x-badge>
            <a href="{{ route('advertiser.campaigns.edit', $campaign) }}" class="btn btn--ghost btn--sm">
                <x-icon name="edit" :size="14" />
                ویرایش
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-side grid-24">
        <div class="stack-24">

            {{-- پیشرفت، بزرگ‌ترین چیز صفحه --}}
            <section class="card card--pad-24" data-reveal>
                <div class="row between wrap" style="gap:16px">
                    <div>
                        <span class="eyebrow">Delivered</span>
                        <p class="h1 figure mt-8">
                            {{ \App\Support\Fmt::num($delivered) }}<span class="dim" style="font-weight:400"> / {{ \App\Support\Fmt::num($campaign->capacity) }}</span>
                        </p>
                        <p class="micro">ویو تحویل‌شده از ظرفیت کل</p>
                    </div>
                    <div class="stack-4" style="text-align:end">
                        <span class="micro">هزینه‌ی تحویل‌شده تا اینجا</span>
                        <span class="h3 figure">{{ \App\Support\Fmt::money($delivered * $campaign->price_per_view) }} <span class="micro dim">تومان</span></span>
                    </div>
                </div>

                <x-progress class="mt-16" :value="$delivered" :max="$campaign->capacity"
                            :tone="$campaign->remaining_capacity > 0 ? 'ink' : 'emerald'"
                            label="پیشرفت کمپین" />
            </section>

            <section class="card card--pad-24" data-reveal>
                <h2 class="h3">جزئیات</h2>

                <dl class="dl mt-16">
                    <div><dt>دسته‌بندی</dt><dd>{{ $campaign->category?->name ?? '—' }}</dd></div>
                    <div><dt>موقعیت هدف</dt><dd>{{ $campaign->province?->name ?? 'همه استان‌ها' }}@if($campaign->city)، {{ $campaign->city->name }}@endif</dd></div>
                    <div><dt>قیمت هر ویو</dt><dd class="figure">{{ \App\Support\Fmt::money($campaign->price_per_view) }} تومان</dd></div>
                    <div><dt>حداقل میانگین ویوی سفیر</dt><dd class="figure">{{ \App\Support\Fmt::num($campaign->min_avg_views) }}</dd></div>
                    <div><dt>ظرفیت باقیمانده</dt>
                        <dd class="figure {{ $campaign->remaining_capacity > 0 ? 'tone-emerald' : 'tone-rose' }}">
                            {{ \App\Support\Fmt::num($campaign->remaining_capacity) }}
                        </dd>
                    </div>
                    <div><dt>بازه‌ی اجرا</dt>
                        <dd>
                            @if($campaign->start_date || $campaign->end_date)
                                @jdate($campaign->start_date) — @jdate($campaign->end_date)
                            @else
                                بدون محدودیت زمانی
                            @endif
                        </dd>
                    </div>

                    <div class="dl__full"><dt>توضیحات</dt><dd class="plain">{{ $campaign->description ?: '—' }}</dd></div>

                    @if($campaign->story_content)
                        <div class="dl__full">
                            <dt>متن استوری</dt>
                            <dd class="plain panel pre-wrap mt-8">{{ $campaign->story_content }}</dd>
                        </div>
                    @endif

                    @if($campaign->admin_note)
                        <div class="dl__full">
                            <dt>یادداشت مدیر</dt>
                            <dd class="plain note note--amber mt-8">
                                <x-icon name="info" :size="15" />
                                <span>{{ $campaign->admin_note }}</span>
                            </dd>
                        </div>
                    @endif
                </dl>
            </section>

            <section class="card card--flush" data-reveal>
                <x-section-head :title="'سفیران تخصیص‌یافته ('.\App\Support\Fmt::num($assignments->count()).')'" />

                <div class="list">
                    @forelse($assignments as $assignment)
                        <div class="list__row">
                            <x-avatar :name="$assignment->ambassador->name" size="sm" tone="mist" />
                            <div class="grow stack-4">
                                <span class="strong truncate">{{ $assignment->ambassador->name }}</span>
                                @if($assignment->ambassador->ambassadorProfile?->ig_username)
                                    <span class="micro ltr">{{ '@'.$assignment->ambassador->ambassadorProfile->ig_username }}</span>
                                @endif
                            </div>
                            <x-badge :tone="$assignment->status->tone()">{{ $assignment->status->label() }}</x-badge>
                        </div>
                    @empty
                        <x-empty-state icon="users" title="هنوز سفیری تخصیص نیافته" tight>
                            @if($campaign->status === \App\Enums\CampaignStatus::Active)
                                از پنل کناری «تخصیص خودکار» را بزنید تا سامانه سفیران مناسب را پیدا کند.
                            @else
                                تخصیص بعد از فعال شدن کمپین انجام می‌شود.
                            @endif
                        </x-empty-state>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- عملیات --}}
        <aside class="stack-16">
            <div class="card" data-reveal>
                <span class="eyebrow">Actions</span>

                <div class="stack-8 mt-16">
                    <a href="{{ route('advertiser.campaigns.edit', $campaign) }}" class="btn btn--ghost btn--block">
                        <x-icon name="edit" :size="14" />
                        ویرایش کمپین
                    </a>

                    @if($campaign->status === \App\Enums\CampaignStatus::Active && $campaign->remaining_capacity > 0)
                        <form method="POST" action="{{ route('advertiser.campaigns.autoAssign', $campaign) }}"
                              data-confirm="سامانه سفیران واجد شرایط را پیدا می‌کند و این کمپین را به آن‌ها پیشنهاد می‌دهد."
                              data-confirm-title="تخصیص خودکار به سفیران"
                              data-confirm-ok="انجام بده">
                            @csrf
                            <button type="submit" class="btn btn--block">
                                <x-icon name="target" :size="14" />
                                تخصیص خودکار به سفیران
                            </button>
                        </form>
                    @endif
                </div>

                @if($campaign->status === \App\Enums\CampaignStatus::Pending)
                    <p class="micro mt-16">این کمپین در انتظار تأیید مدیر است؛ تا آن زمان تخصیصی انجام نمی‌شود.</p>
                @elseif($campaign->remaining_capacity <= 0)
                    <p class="micro mt-16">ظرفیت این کمپین کامل شده است.</p>
                @endif
            </div>
        </aside>
    </div>
@endsection
