@extends('layouts.app')

@section('title', 'کمپین‌های من')

@section('content')
    <x-page-header eyebrow="Campaigns" title="کمپین‌های من"
                   lead="هر کارت، وضعیت و میزان پرشدن ظرفیت را نشان می‌دهد.">
        <x-slot:actions>
            <a href="{{ route('advertiser.campaigns.create') }}" class="btn">
                <x-icon name="plus" :size="15" />
                کمپین جدید
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="mb-24" data-reveal>
        <x-filter-chips
            route="advertiser.campaigns.index"
            :current="request('status')"
            label="فیلتر بر اساس وضعیت"
            :options="['' => 'همه'] + \App\Enums\CampaignStatus::options()" />
    </div>

    <div class="grid grid-2 grid-20">
        @forelse($campaigns as $campaign)
            @php $delivered = $campaign->capacity - $campaign->remaining_capacity; @endphp

            <a href="{{ route('advertiser.campaigns.show', $campaign) }}" class="card card--link" data-reveal>
                <div class="row between wrap" style="gap:12px">
                    <h2 class="h4 grow">{{ $campaign->title }}</h2>
                    <x-badge :tone="$campaign->status->tone()">{{ $campaign->status->label() }}</x-badge>
                </div>

                <p class="meta mt-8">{{ $campaign->category?->name ?? 'بدون دسته‌بندی' }}</p>

                <div class="mt-16">
                    <x-progress :value="$delivered" :max="$campaign->capacity"
                                :tone="$campaign->remaining_capacity > 0 ? 'ink' : 'emerald'"
                                label="پیشرفت ظرفیت" />
                    <p class="micro mt-8">
                        {{ \App\Support\Fmt::num($delivered) }} از {{ \App\Support\Fmt::num($campaign->capacity) }} ویو
                    </p>
                </div>

                <div class="grid grid-3 keep mt-16" style="gap:12px">
                    <div class="stack-4">
                        <span class="micro">ظرفیت کل</span>
                        <span class="strong figure">{{ \App\Support\Fmt::num($campaign->capacity) }}</span>
                    </div>
                    <div class="stack-4">
                        <span class="micro">باقیمانده</span>
                        <span class="strong figure {{ $campaign->remaining_capacity > 0 ? 'tone-emerald' : 'dim' }}">
                            {{ \App\Support\Fmt::num($campaign->remaining_capacity) }}
                        </span>
                    </div>
                    <div class="stack-4">
                        <span class="micro">قیمت هر ویو</span>
                        <span class="strong figure">{{ \App\Support\Fmt::money($campaign->price_per_view) }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="card span-full">
                <x-empty-state icon="megaphone" title="کمپینی پیدا نشد">
                    @if(request('status'))
                        با این وضعیت چیزی نیست.
                        <a href="{{ route('advertiser.campaigns.index') }}" class="strong">همه را ببینید</a>.
                    @else
                        هنوز کمپینی نساخته‌اید.
                        <span style="display:block;margin-block-start:14px">
                            <a href="{{ route('advertiser.campaigns.create') }}" class="btn btn--sm">ساخت اولین کمپین</a>
                        </span>
                    @endif
                </x-empty-state>
            </div>
        @endforelse
    </div>

    <div class="mt-24">{{ $campaigns->links() }}</div>
@endsection
