@extends('layouts.app')

@section('title', 'ثبت‌ویوهای من')

@section('content')
    <x-page-header eyebrow="My submissions" title="ثبت‌ویوهای من"
                   lead="هر ویویی که ارسال کرده‌اید و نتیجه‌ی بررسی آن." />

    <div class="stack-16">
        @forelse($submissions as $submission)
            @php $shot = $submission->screenshot_path
                ? \Illuminate\Support\Facades\Storage::disk('uploads')->url($submission->screenshot_path)
                : null; @endphp

            <article class="card row-16 wrap" data-reveal>
                @if($shot)
                    <a href="{{ $shot }}" target="_blank" rel="noopener" class="thumb thumb--64"
                       aria-label="دیدن اسکرین‌شات در اندازه‌ی کامل">
                        <img src="{{ $shot }}" alt="اسکرین‌شات آمار استوری کمپین {{ $submission->campaign->title }}">
                    </a>
                @else
                    <span class="thumb thumb--64 thumb--empty" aria-hidden="true">
                        <x-icon name="image" :size="18" />
                    </span>
                @endif

                <div class="grow stack-4">
                    <h2 class="h4">{{ $submission->campaign->title }}</h2>
                    <p class="meta">
                        <span class="figure strong">{{ \App\Support\Fmt::num($submission->views_count) }}</span> ویو
                        <span class="dim"> · </span>
                        @ago($submission->submitted_at)
                    </p>
                    @if($submission->review_note)
                        <p class="micro tone-amber mt-8">یادداشت بررسی: {{ $submission->review_note }}</p>
                    @endif
                </div>

                <x-badge :tone="$submission->status->tone()">{{ $submission->status->label() }}</x-badge>
            </article>
        @empty
            <div class="card">
                <x-empty-state icon="upload" title="هنوز ویویی ثبت نکرده‌اید">
                    وقتی یک تبلیغ را پذیرفتید و استوری را منتشر کردید، از
                    <a href="{{ route('ambassador.assignments.index') }}" class="strong">تبلیغات من</a>
                    عدد ویو را ثبت کنید.
                </x-empty-state>
            </div>
        @endforelse
    </div>

    <div class="mt-24">{{ $submissions->links() }}</div>
@endsection
