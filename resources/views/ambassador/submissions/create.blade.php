@extends('layouts.app')

@section('title', 'ثبت ویو')

@section('content')
    @php $campaign = $assignment->campaign; @endphp

    <x-page-header eyebrow="Submit views" title="ثبت ویوی استوری"
                   :back="route('ambassador.assignments.index')" back-label="بازگشت به تبلیغات من"
                   lead="عدد ویو را از بخش آمار استوری بردارید و همان صفحه را اسکرین‌شات بگیرید." />

    <div class="grid grid-side grid-24">
        <div class="stack-24">
            <form method="POST" action="{{ route('ambassador.submissions.store', $assignment) }}"
                  enctype="multipart/form-data" class="card card--pad-24 stack-20" data-reveal>
                @csrf

                <x-field label="تعداد ویو" name="views_count" required
                         hint="عدد دقیق «بازدید» استوری — نه لایک و نه دنبال‌کننده.">
                    <input type="number" id="f-views_count" name="views_count" value="{{ old('views_count') }}"
                           class="input figure @error('views_count') input--err @enderror"
                           required min="1" inputmode="numeric" placeholder="۱۲۰۰" autofocus>
                </x-field>

                <x-field label="اسکرین‌شات آمار استوری" name="screenshot" required
                         hint="حداکثر ۵ مگابایت. تصویر باید عدد ویو را واضح نشان دهد.">
                    <input type="file" id="f-screenshot" name="screenshot" accept="image/*" required
                           class="file @error('screenshot') input--err @enderror" data-preview="shot-preview">
                    <div class="preview" id="shot-preview" hidden></div>
                </x-field>

                <x-field label="توضیح" name="description" optional
                         hint="اگر نکته‌ای هست که بررسی را سریع‌تر می‌کند، اینجا بنویسید.">
                    <textarea id="f-description" name="description" rows="3" class="textarea">{{ old('description') }}</textarea>
                </x-field>

                <div class="formbar">
                    <button type="submit" class="btn">
                        <x-icon name="upload" :size="15" />
                        ارسال برای تأیید
                    </button>
                    <a href="{{ route('ambassador.assignments.index') }}" class="btn btn--quiet">انصراف</a>
                    <p class="micro push">پس از تأیید مدیر، درآمد به کیف پول شما واریز می‌شود.</p>
                </div>
            </form>
        </div>

        {{-- کارتِ کمپین، همیشه جلوی چشم --}}
        <aside class="stack-16">
            <div class="card" data-reveal>
                <span class="eyebrow">Campaign</span>
                <h2 class="h4 mt-8">{{ $campaign->title }}</h2>
                <p class="meta mt-8">{{ $campaign->advertiser->name }}</p>

                <hr class="mt-16">

                <div class="stack-12 mt-16">
                    <div class="row between">
                        <span class="micro">درآمد هر ویو</span>
                        <span class="strong figure">{{ \App\Support\Fmt::money($campaign->price_per_view) }} <span class="micro dim">تومان</span></span>
                    </div>
                    <div class="row between">
                        <span class="micro">ظرفیت باقیمانده</span>
                        <span class="strong figure">{{ \App\Support\Fmt::num($campaign->remaining_capacity) }}</span>
                    </div>
                    @if($campaign->end_date)
                        <div class="row between">
                            <span class="micro">مهلت</span>
                            <span class="strong">@jdate($campaign->end_date)</span>
                        </div>
                    @endif
                </div>
            </div>

            @if($campaign->story_content)
                <div class="card" data-reveal>
                    <span class="eyebrow">Story text</span>
                    <p class="body pre-wrap mt-12">{{ $campaign->story_content }}</p>
                    <button type="button" class="btn btn--ghost btn--sm mt-16"
                            data-copy="{{ $campaign->story_content }}" aria-label="کپی متن استوری">
                        <x-icon name="copy" :size="14" />
                        کپی متن استوری
                    </button>
                </div>
            @endif
        </aside>
    </div>
@endsection
