@extends('layouts.app')

@section('title', 'تبلیغات من')

@section('content')
    <x-page-header eyebrow="Assignments" title="تبلیغات من"
                   lead="کمپین‌هایی که به شما تخصیص داده شده‌اند. تازه‌ها را بپذیرید و بعد از انتشار استوری، ویو را ثبت کنید." />

    <div class="mb-24" data-reveal>
        <x-filter-chips
            route="ambassador.assignments.index"
            :current="request('status')"
            label="فیلتر بر اساس وضعیت"
            :options="[
                '' => 'همه',
                'assigned' => 'منتظر پاسخ شما',
                'accepted' => 'در حال اجرا',
                'completed' => 'تکمیل‌شده',
                'declined' => 'ردشده',
            ]" />
    </div>

    <div class="stack-16">
        @forelse($assignments as $assignment)
            @php $campaign = $assignment->campaign; @endphp

            <article class="card" data-reveal>
                <div class="row between wrap" style="gap:16px">
                    <div class="stack-4 grow">
                        <div class="row-8 wrap">
                            <h2 class="h4">{{ $campaign->title }}</h2>
                            <x-badge :tone="$assignment->status->tone()">{{ $assignment->status->label() }}</x-badge>
                        </div>
                        <p class="meta">{{ $campaign->advertiser->name }}</p>
                    </div>

                    <div class="row-8 wrap">
                        @if($assignment->status === \App\Enums\AssignmentStatus::Assigned)
                            <form method="POST" action="{{ route('ambassador.assignments.accept', $assignment) }}">
                                @csrf
                                <button type="submit" class="btn btn--emerald btn--sm">
                                    <x-icon name="check" :size="14" />
                                    می‌پذیرم
                                </button>
                            </form>

                            <form method="POST" action="{{ route('ambassador.assignments.decline', $assignment) }}"
                                  data-reason-form
                                  data-reason-title="رد کردن «{{ $campaign->title }}»"
                                  data-reason-label="دلیل رد — اختیاری"
                                  data-reason-ok="رد می‌کنم">
                                @csrf
                                <input type="hidden" name="reason" value="" data-reason-field>
                                <button type="submit" class="btn btn--ghost btn--sm">رد می‌کنم</button>
                            </form>
                        @endif

                        @if($assignment->status === \App\Enums\AssignmentStatus::Accepted)
                            <a href="{{ route('ambassador.submissions.create', $assignment) }}" class="btn btn--sm">
                                <x-icon name="upload" :size="14" />
                                ثبت ویو
                            </a>
                        @endif
                    </div>
                </div>

                <div class="row-24 wrap mt-16">
                    <div class="stack-4">
                        <span class="micro">درآمد هر ویو</span>
                        <span class="strong figure">{{ \App\Support\Fmt::money($campaign->price_per_view) }} <span class="micro dim">تومان</span></span>
                    </div>
                    <div class="stack-4">
                        <span class="micro">ظرفیت باقیمانده</span>
                        <span class="strong figure">{{ \App\Support\Fmt::num($campaign->remaining_capacity) }}</span>
                    </div>
                    @if($campaign->end_date)
                        <div class="stack-4">
                            <span class="micro">مهلت</span>
                            <span class="strong">@jdate($campaign->end_date)</span>
                        </div>
                    @endif
                </div>

                @if($campaign->story_content)
                    <div class="panel mt-16">
                        <p class="micro">متنی که باید در استوری بیاید</p>
                        <p class="body pre-wrap mt-8">{{ $campaign->story_content }}</p>
                        <button type="button" class="btn btn--ghost btn--xs mt-12"
                                data-copy="{{ $campaign->story_content }}" aria-label="کپی متن استوری">
                            <x-icon name="copy" :size="13" />
                            کپی متن
                        </button>
                    </div>
                @endif

                @if($assignment->status === \App\Enums\AssignmentStatus::Declined && $assignment->decline_reason)
                    <p class="micro mt-12">دلیل رد شما: {{ $assignment->decline_reason }}</p>
                @endif
            </article>
        @empty
            <div class="card">
                <x-empty-state icon="inbox" title="اینجا خالی است">
                    @if(request('status'))
                        با این فیلتر چیزی پیدا نشد.
                        <a href="{{ route('ambassador.assignments.index') }}" class="strong">همه را ببینید</a>.
                    @else
                        هنوز کمپینی به شما تخصیص داده نشده. مطمئن شوید
                        <a href="{{ route('ambassador.profile.edit') }}" class="strong">پروفایل‌تان</a>
                        کامل و تأییدشده است — تخصیص خودکار از روی همان انجام می‌شود.
                    @endif
                </x-empty-state>
            </div>
        @endforelse
    </div>

    <div class="mt-24">{{ $assignments->links() }}</div>
@endsection
