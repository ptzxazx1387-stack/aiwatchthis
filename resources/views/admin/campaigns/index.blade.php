@extends('layouts.app')

@section('title', 'مدیریت کمپین‌ها')

@section('content')
    <x-page-header eyebrow="Campaigns" title="مدیریت کمپین‌ها"
                   lead="همه‌ی کمپین‌های سامانه. برای تأیید یا توقف، وارد کمپین شوید." />

    <div class="mb-24" data-reveal>
        <x-filter-chips
            route="admin.campaigns.index"
            :current="request('status')"
            label="فیلتر بر اساس وضعیت"
            :options="['' => 'همه'] + \App\Enums\CampaignStatus::options()" />
    </div>

    <div class="card card--flush" data-reveal>
        <div class="tablewrap">
            <table class="table table--stack">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>تبلیغ‌دهنده</th>
                        <th>پیشرفت</th>
                        <th>وضعیت</th>
                        <th>ثبت</th>
                        <th><span class="sr-only">عملیات</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        @php $delivered = $campaign->capacity - $campaign->remaining_capacity; @endphp
                        <tr>
                            <td data-th="عنوان">
                                <a href="{{ route('admin.campaigns.show', $campaign) }}" class="strong">{{ $campaign->title }}</a>
                            </td>
                            <td data-th="تبلیغ‌دهنده">{{ $campaign->advertiser->name }}</td>
                            <td data-th="پیشرفت" style="min-inline-size:160px">
                                <span class="num micro">
                                    {{ \App\Support\Fmt::num($delivered) }} / {{ \App\Support\Fmt::num($campaign->capacity) }}
                                </span>
                                <x-progress class="mt-8" :value="$delivered" :max="$campaign->capacity"
                                            :tone="$campaign->remaining_capacity > 0 ? 'ink' : 'emerald'"
                                            label="پیشرفت کمپین" />
                            </td>
                            <td data-th="وضعیت">
                                <x-badge :tone="$campaign->status->tone()">{{ $campaign->status->label() }}</x-badge>
                            </td>
                            <td data-th="ثبت" class="micro">@jshort($campaign->created_at)</td>
                            <td data-th="">
                                <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn--ghost btn--xs">بررسی</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" data-th="">
                                <x-empty-state icon="megaphone" title="کمپینی یافت نشد" tight>
                                    @if(request('status'))
                                        با این وضعیت کمپینی نیست.
                                        <a href="{{ route('admin.campaigns.index') }}" class="strong">همه را ببینید</a>.
                                    @endif
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card__foot">{{ $campaigns->links() }}</div>
    </div>
@endsection
