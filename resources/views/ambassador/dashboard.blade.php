@extends('layouts.app')

@section('title', 'داشبورد سفیر')

@section('content')
    @php
        $profile = auth()->user()->ambassadorProfile;
        $needsProfile = ! $profile;
        $awaitingReview = $profile && $profile->status === \App\Enums\ProfileStatus::Pending;
    @endphp

    <x-page-header eyebrow="Ambassador" title="سلام {{ auth()->user()->name }}"
                   lead="خلاصه‌ی امروزتان — و کاری که همین حالا می‌توانید انجام دهید.">
        <x-slot:actions>
            <a href="{{ route('ambassador.campaigns.index') }}" class="btn">
                <x-icon name="search" :size="15" />
                بازار کمپین‌ها
            </a>
        </x-slot:actions>
    </x-page-header>

    {{-- یک کار، بالای صفحه، فقط وقتی واقعاً لازم است --}}
    @if($needsProfile)
        <div class="note note--amber mb-24" data-reveal>
            <x-icon name="alert" :size="16" />
            <p class="grow">
                هنوز پروفایل اینستاگرام‌تان را ثبت نکرده‌اید و به همین دلیل هیچ تبلیغی به شما
                تخصیص داده نمی‌شود. <a href="{{ route('ambassador.profile.edit') }}">همین حالا کامل کنید</a> — کمتر از یک دقیقه.
            </p>
        </div>
    @elseif($awaitingReview)
        <div class="note note--amber mb-24" data-reveal>
            <x-icon name="clock" :size="16" />
            <p class="grow">پروفایل شما ثبت شد و در انتظار تأیید مدیر است. به‌محض تأیید، کمپین‌ها به شما پیشنهاد می‌شوند.</p>
        </div>
    @endif

    {{-- کیف پول: بزرگ‌ترین عدد صفحه --}}
    <div class="ledger mb-24" data-reveal>
        <div>
            <span class="eyebrow">Balance</span>
            <p class="ledger__value">
                {{ \App\Support\Fmt::money($stats['wallet_balance']) }}<span class="ledger__unit"> تومان</span>
            </p>
            <p class="ledger__side">درآمد تأییدشده‌ی شما، آماده‌ی برداشت</p>
        </div>
        <a href="{{ route('wallet.index') }}" class="btn">
            <x-icon name="wallet" :size="15" />
            کیف پول و برداشت
        </a>
    </div>

    <div class="grid grid-4 keep mb-24">
        <x-stat-card label="تخصیص تازه" :value="$stats['assigned_campaigns']" icon="inbox" flag
                     :href="route('ambassador.assignments.index', ['status' => 'assigned'])"
                     foot="منتظر پذیرش شماست" />
        <x-stat-card label="در حال اجرا" :value="$stats['accepted_campaigns']" icon="clock"
                     :href="route('ambassador.assignments.index', ['status' => 'accepted'])"
                     foot="پذیرفته‌اید، هنوز ویو ثبت نشده" />
        <x-stat-card label="تکمیل‌شده" :value="$stats['completed_campaigns']" icon="check-circle"
                     :href="route('ambassador.assignments.index', ['status' => 'completed'])" />
        <x-stat-card label="ویو ۷ روز اخیر" :value="$stats['total_views_7d']" icon="eye"
                     foot="میانگین این عدد، سطح شما را می‌سازد" />
    </div>

    <section class="card card--flush" data-reveal>
        <x-section-head title="تبلیغات فعال من" :href="route('ambassador.assignments.index')" />

        <div class="list">
            @forelse($myAssignments as $assignment)
                <div class="list__row">
                    <div class="grow stack-4">
                        <span class="strong truncate">{{ $assignment->campaign->title }}</span>
                        <span class="micro">{{ $assignment->campaign->advertiser->name }}</span>
                    </div>

                    <x-badge :tone="$assignment->status->tone()">{{ $assignment->status->label() }}</x-badge>

                    @if($assignment->status === \App\Enums\AssignmentStatus::Accepted)
                        <a href="{{ route('ambassador.submissions.create', $assignment) }}" class="btn btn--sm">
                            <x-icon name="upload" :size="13" />
                            ثبت ویو
                        </a>
                    @endif
                </div>
            @empty
                <x-empty-state icon="megaphone" title="هنوز تبلیغ فعالی ندارید">
                    وقتی کمپینی به شما تخصیص داده شود اینجا می‌آید.
                    تا آن موقع می‌توانید <a href="{{ route('ambassador.campaigns.index') }}" class="strong">بازار کمپین‌ها</a>
                    را ببینید و بدانید چه تبلیغ‌هایی در جریان است.
                </x-empty-state>
            @endforelse
        </div>
    </section>
@endsection
