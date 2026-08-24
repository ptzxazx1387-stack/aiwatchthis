@extends('layouts.app')

@section('title', $campaign->title)

@section('content')
    @php
        $delivered = $campaign->capacity - $campaign->remaining_capacity;
        $assignments = $campaign->assignments;
        $submissions = $campaign->viewSubmissions;
    @endphp

    <x-page-header eyebrow="Campaign review" :title="$campaign->title"
                   :back="route('admin.campaigns.index')" back-label="بازگشت به کمپین‌ها"
                   :lead="$campaign->advertiser->name">
        <x-slot:actions>
            <x-badge :tone="$campaign->status->tone()">{{ $campaign->status->label() }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-side grid-24">
        <div class="stack-24">

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
                        <span class="micro">ارزش کل کمپین</span>
                        <span class="h3 figure">{{ \App\Support\Fmt::money($campaign->capacity * $campaign->price_per_view) }} <span class="micro dim">تومان</span></span>
                    </div>
                </div>

                <x-progress class="mt-16" :value="$delivered" :max="$campaign->capacity"
                            :tone="$campaign->remaining_capacity > 0 ? 'ink' : 'emerald'"
                            label="پیشرفت کمپین" />
            </section>

            <section class="card card--pad-24" data-reveal>
                <h2 class="h3">جزئیات</h2>

                <dl class="dl mt-16">
                    <div><dt>تبلیغ‌دهنده</dt><dd>{{ $campaign->advertiser->name }}</dd></div>
                    <div><dt>دسته‌بندی</dt><dd>{{ $campaign->category?->name ?? '—' }}</dd></div>
                    <div><dt>استان هدف</dt><dd>{{ $campaign->province?->name ?? 'همه' }}</dd></div>
                    <div><dt>شهر هدف</dt><dd>{{ $campaign->city?->name ?? 'همه' }}</dd></div>
                    <div><dt>قیمت هر ویو</dt><dd class="figure">{{ \App\Support\Fmt::money($campaign->price_per_view) }} تومان</dd></div>
                    <div><dt>حداقل میانگین ویوی سفیر</dt><dd class="figure">{{ \App\Support\Fmt::num($campaign->min_avg_views) }}</dd></div>
                    <div><dt>ظرفیت باقیمانده</dt>
                        <dd class="figure {{ $campaign->remaining_capacity > 0 ? 'tone-emerald' : 'tone-rose' }}">
                            {{ \App\Support\Fmt::num($campaign->remaining_capacity) }}
                        </dd>
                    </div>
                    <div><dt>تاریخ ثبت</dt><dd>@jdate($campaign->created_at)</dd></div>

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
                        <x-empty-state icon="users" title="هنوز سفیری تخصیص نیافته" tight />
                    @endforelse
                </div>
            </section>
        </div>

        {{-- عملیات مدیر --}}
        <aside class="stack-16">
            <div class="card" data-reveal>
                <span class="eyebrow">Actions</span>

                <div class="stack-8 mt-16">
                    @if($campaign->status === \App\Enums\CampaignStatus::Pending)
                        <form method="POST" action="{{ route('admin.campaigns.approve', $campaign) }}"
                              data-confirm="کمپین فعال می‌شود و قابل تخصیص به سفیران خواهد بود."
                              data-confirm-title="تأیید و فعال‌سازی کمپین"
                              data-confirm-ok="تأیید و فعال کن">
                            @csrf
                            <button type="submit" class="btn btn--emerald btn--block">
                                <x-icon name="check" :size="14" />
                                تأیید و فعال‌سازی
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.campaigns.reject', $campaign) }}"
                              data-reason-form
                              data-reason-tone="rose"
                              data-reason-title="رد کمپین «{{ $campaign->title }}»"
                              data-reason-label="دلیل رد — برای تبلیغ‌دهنده ارسال می‌شود"
                              data-reason-ok="رد می‌کنم">
                            @csrf
                            <input type="hidden" name="admin_note" value="" data-reason-field>
                            <button type="submit" class="btn btn--ghost btn--block">رد کمپین</button>
                        </form>
                    @endif

                    @if($campaign->status === \App\Enums\CampaignStatus::Active)
                        <form method="POST" action="{{ route('admin.campaigns.autoAssign', $campaign) }}"
                              data-confirm="سفیران واجد شرایط پیدا می‌شوند و کمپین به آن‌ها پیشنهاد داده می‌شود."
                              data-confirm-title="تخصیص خودکار"
                              data-confirm-ok="انجام بده">
                            @csrf
                            <button type="submit" class="btn btn--block">
                                <x-icon name="target" :size="14" />
                                تخصیص خودکار به سفیران
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.campaigns.pause', $campaign) }}"
                              data-confirm="کمپین متوقف می‌شود و تخصیص جدیدی انجام نمی‌گیرد."
                              data-confirm-title="توقف کمپین"
                              data-confirm-ok="متوقف کن">
                            @csrf
                            <button type="submit" class="btn btn--ghost btn--block">
                                <x-icon name="pause" :size="14" />
                                توقف کمپین
                            </button>
                        </form>
                    @endif

                    @if(! in_array($campaign->status, [\App\Enums\CampaignStatus::Pending, \App\Enums\CampaignStatus::Active], true))
                        <p class="micro">برای این وضعیت عملیاتی در دسترس نیست.</p>
                    @endif
                </div>
            </div>

            <div class="card card--flush" data-reveal>
                <x-section-head :title="'ثبت‌ویوها ('.\App\Support\Fmt::num($submissions->count()).')'"
                                :href="route('admin.submissions.index')" link-label="صف بررسی" />
                <div class="list">
                    @forelse($submissions as $s)
                        <div class="list__row">
                            <div class="grow stack-4">
                                <span class="strong truncate">{{ $s->ambassador->name }}</span>
                                <span class="micro figure">{{ \App\Support\Fmt::num($s->views_count) }} ویو</span>
                            </div>
                            <x-badge :tone="$s->status->tone()">{{ $s->status->label() }}</x-badge>
                        </div>
                    @empty
                        <x-empty-state icon="upload" title="ثبت‌ویویی نیست" tight />
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
@endsection
