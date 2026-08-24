@extends('layouts.app')

@section('title', 'بازار کمپین‌ها')

@section('content')
    @php
        $hasFilter = request()->hasAny(['province_id', 'city_id', 'category_id']);
        $cities = request('province_id')
            ? \App\Models\City::where('province_id', request('province_id'))->orderBy('name')->get()
            : collect();
    @endphp

    <x-page-header eyebrow="Marketplace" title="بازار کمپین‌ها"
                   lead="کمپین‌های فعالی که همین حالا ظرفیت دارند. تخصیص خودکار از روی پروفایل شما انجام می‌شود." />

    <form method="GET" class="toolbar mb-24" data-reveal>
        <x-field label="استان">
            <select name="province_id" class="select" data-autosubmit data-geo-province
                    data-geo-url="{{ route('geo.cities') }}" aria-label="فیلتر استان">
                <option value="">همه استان‌ها</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}" @selected(request('province_id') == $province->id)>{{ $province->name }}</option>
                @endforeach
            </select>
        </x-field>

        <x-field label="شهر">
            <select name="city_id" class="select" data-autosubmit data-geo-city
                    data-blank-label="همه شهرها" aria-label="فیلتر شهر">
                <option value="">همه شهرها</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" @selected(request('city_id') == $city->id)>{{ $city->name }}</option>
                @endforeach
            </select>
        </x-field>

        <x-field label="دسته‌بندی">
            <select name="category_id" class="select" data-autosubmit aria-label="فیلتر دسته‌بندی">
                <option value="">همه دسته‌ها</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </x-field>

        {{-- بدون جاوااسکریپت هم فیلتر باید اعمال شود --}}
        <button type="submit" class="btn btn--ghost btn--sm">اعمال</button>

        @if($hasFilter)
            <a href="{{ route('ambassador.campaigns.index') }}" class="btn btn--quiet btn--sm">
                <x-icon name="x" :size="13" />
                پاک کردن فیلترها
            </a>
        @endif
    </form>

    <div class="grid grid-2 grid-20">
        @forelse($campaigns as $campaign)
            <article class="card" data-reveal>
                <div class="row between wrap" style="gap:12px">
                    <h2 class="h4 grow">{{ $campaign->title }}</h2>
                    <x-badge tone="emerald">ظرفیت باز</x-badge>
                </div>

                <p class="meta mt-8">{{ $campaign->advertiser->name }}</p>

                <div class="row-8 wrap mt-12">
                    @if($campaign->category)
                        <span class="chip" style="pointer-events:none">
                            <x-icon name="tag" :size="12" />
                            {{ $campaign->category->name }}
                        </span>
                    @endif
                    <span class="chip" style="pointer-events:none">
                        <x-icon name="pin" :size="12" />
                        {{ $campaign->province?->name ?? 'همه استان‌ها' }}@if($campaign->city)، {{ $campaign->city->name }}@endif
                    </span>
                </div>

                <div class="grid grid-3 keep mt-16" style="gap:12px">
                    <div class="stack-4">
                        <span class="micro">درآمد هر ویو</span>
                        <span class="strong figure">{{ \App\Support\Fmt::money($campaign->price_per_view) }}</span>
                    </div>
                    <div class="stack-4">
                        <span class="micro">ظرفیت باقیمانده</span>
                        <span class="strong figure tone-emerald">{{ \App\Support\Fmt::num($campaign->remaining_capacity) }}</span>
                    </div>
                    <div class="stack-4">
                        <span class="micro">حداقل ویو لازم</span>
                        <span class="strong figure">{{ \App\Support\Fmt::num($campaign->min_avg_views) }}</span>
                    </div>
                </div>

                <div class="mt-16">
                    <x-progress :value="$campaign->capacity - $campaign->remaining_capacity" :max="$campaign->capacity"
                                label="میزان پرشدن ظرفیت" />
                    <p class="micro mt-8">
                        {{ \App\Support\Fmt::num($campaign->capacity - $campaign->remaining_capacity) }} از
                        {{ \App\Support\Fmt::num($campaign->capacity) }} ویو تأمین شده
                    </p>
                </div>

                @if($campaign->description)
                    <p class="body panel mt-16">{{ $campaign->description }}</p>
                @endif
            </article>
        @empty
            <div class="card span-full">
                <x-empty-state icon="search" title="کمپینی با این مشخصات پیدا نشد">
                    @if($hasFilter)
                        فیلترها را بازتر کنید یا
                        <a href="{{ route('ambassador.campaigns.index') }}" class="strong">همه‌ی کمپین‌ها</a>
                        را ببینید.
                    @else
                        الان کمپین فعالی با ظرفیت باز وجود ندارد. کمی بعد دوباره سر بزنید.
                    @endif
                </x-empty-state>
            </div>
        @endforelse
    </div>

    <div class="mt-24">{{ $campaigns->links() }}</div>
@endsection
