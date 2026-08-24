@extends('layouts.app')

@section('title', 'تأیید ثبت‌ویوها')

@section('content')
    <x-page-header eyebrow="Review queue" title="تأیید ثبت‌ویوها"
                   lead="اسکرین‌شات را با عدد اعلام‌شده مقایسه کنید. تأیید یعنی واریز فوری درآمد به کیف پول سفیر." />

    <div class="mb-24" data-reveal>
        <x-filter-chips
            route="admin.submissions.index"
            :current="request('status')"
            label="فیلتر بر اساس وضعیت"
            :options="[
                '' => 'صف بررسی',
                'approved' => 'تأییدشده',
                'rejected' => 'ردشده',
            ]" />
    </div>

    <div class="stack-16">
        @forelse($submissions as $submission)
            @php
                $shot = $submission->screenshot_path
                    ? \Illuminate\Support\Facades\Storage::disk('uploads')->url($submission->screenshot_path)
                    : null;
                $payout = $submission->views_count * $submission->campaign->price_per_view;
            @endphp

            <article class="card card--pad-24" data-reveal>
                <div class="row-24 row-top wrap">

                    {{-- مدرک --}}
                    @if($shot)
                        <a href="{{ $shot }}" target="_blank" rel="noopener" class="thumb thumb--128"
                           aria-label="دیدن اسکرین‌شات {{ $submission->ambassador->name }} در اندازه‌ی کامل">
                            <img src="{{ $shot }}" alt="اسکرین‌شات آمار استوری، ارسال‌شده توسط {{ $submission->ambassador->name }}">
                        </a>
                    @else
                        <span class="thumb thumb--128 thumb--empty">بدون تصویر</span>
                    @endif

                    {{-- ادعا --}}
                    <div class="grow" style="min-inline-size:260px">
                        <div class="row between wrap" style="gap:12px">
                            <div class="stack-4">
                                <h2 class="h4">{{ $submission->ambassador->name }}</h2>
                                <p class="micro">{{ $submission->campaign->title }}</p>
                            </div>
                            <x-badge :tone="$submission->status->tone()">{{ $submission->status->label() }}</x-badge>
                        </div>

                        <div class="row-24 wrap mt-16">
                            <div class="stack-4">
                                <span class="micro">ویوی اعلام‌شده</span>
                                <span class="h3 figure">{{ \App\Support\Fmt::num($submission->views_count) }}</span>
                            </div>
                            <div class="stack-4">
                                <span class="micro">مبلغ قابل واریز</span>
                                <span class="h3 figure">{{ \App\Support\Fmt::money($payout) }} <span class="micro dim">تومان</span></span>
                            </div>
                            <div class="stack-4">
                                <span class="micro">زمان ارسال</span>
                                <span class="strong">@ago($submission->submitted_at)</span>
                            </div>
                        </div>

                        @if($submission->description)
                            <p class="body panel mt-16">{{ $submission->description }}</p>
                        @endif

                        @if($submission->review_note)
                            <div class="note note--amber mt-16">
                                <x-icon name="info" :size="15" />
                                <span>یادداشت بررسی: {{ $submission->review_note }}</span>
                            </div>
                        @endif

                        @if($submission->status === \App\Enums\SubmissionStatus::Pending)
                            <div class="row-8 wrap mt-16">
                                <form method="POST" action="{{ route('admin.submissions.approve', $submission) }}"
                                      data-confirm="{{ \App\Support\Fmt::money($payout) }} تومان به کیف پول {{ $submission->ambassador->name }} واریز می‌شود. این کار برگشت‌پذیر نیست."
                                      data-confirm-title="تأیید و واریز درآمد"
                                      data-confirm-ok="تأیید و واریز">
                                    @csrf
                                    <button type="submit" class="btn btn--emerald btn--sm">
                                        <x-icon name="check" :size="14" />
                                        تأیید و واریز
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.submissions.reject', $submission) }}"
                                      data-reason-form
                                      data-reason-required="true"
                                      data-reason-tone="rose"
                                      data-reason-title="رد ثبت‌ویوی {{ $submission->ambassador->name }}"
                                      data-reason-label="دلیل رد — برای سفیر ارسال می‌شود"
                                      data-reason-ok="رد می‌کنم">
                                    @csrf
                                    <input type="hidden" name="review_note" value="" data-reason-field>
                                    <button type="submit" class="btn btn--ghost btn--sm">رد کردن</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="card">
                <x-empty-state icon="check-circle" title="چیزی برای بررسی نیست">
                    @if(request('status'))
                        با این فیلتر موردی نیست.
                        <a href="{{ route('admin.submissions.index') }}" class="strong">صف بررسی</a> را ببینید.
                    @else
                        صف بررسی خالی است. همه‌ی ویوهای ارسال‌شده رسیدگی شده‌اند.
                    @endif
                </x-empty-state>
            </div>
        @endforelse
    </div>

    <div class="mt-24">{{ $submissions->links() }}</div>
@endsection
