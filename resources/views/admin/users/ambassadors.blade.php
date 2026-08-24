@extends('layouts.app')

@section('title', 'مدیریت سفیران')

@section('content')
    <x-page-header eyebrow="Ambassadors" title="مدیریت سفیران" lead="پروفایل‌ها را تأیید کنید، سطح بدهید یا دسترسی فعالیت را موقتاً ببندید." />

    <x-filter-chips
        route="admin.ambassadors.index"
        param="status"
        :current="request('status')"
        :options="['' => 'همه', 'pending' => 'در انتظار تأیید', 'active' => 'فعال', 'suspended' => 'معلق']"
        class="mb-24" />

    <div class="grid grid-2 grid-20">
        @forelse($profiles as $profile)
            <article class="card stack-16" data-reveal>
                <div class="row between wrap" style="gap:12px">
                    <div class="row-8">
                        <x-avatar :name="$profile->user->name" size="lg" tone="mist" />
                        <div class="stack-4 grow">
                            <h2 class="h4 truncate">{{ $profile->user->name }}</h2>
                            <a href="https://instagram.com/{{ ltrim($profile->ig_username, '@') }}" target="_blank" rel="noopener" class="micro ltr strong" style="display:inline-flex;align-items:center;gap:6px">
                                <x-icon name="instagram" :size="13" />
                                {{ '@'.ltrim($profile->ig_username, '@') }}
                            </a>
                        </div>
                    </div>
                    <x-badge :tone="$profile->status->tone()">{{ $profile->status->label() }}</x-badge>
                </div>

                <div class="kpi-strip">
                    <div><div class="micro">دنبال‌کننده</div><div class="strong figure">@num($profile->followers_count)</div></div>
                    <div><div class="micro">میانگین ویو ۷ روز</div><div class="strong figure">@num($profile->avg_views_7d)</div></div>
                    <div><div class="micro">سطح</div><div class="strong truncate">{{ $profile->group?->name ?? '—' }}</div></div>
                    <div><div class="micro">دسته</div><div class="strong truncate">{{ $profile->category?->name ?? '—' }}</div></div>
                </div>

                <p class="micro">
                    <x-icon name="pin" :size="12" class="dim" style="display:inline-block;vertical-align:-2px" />
                    {{ $profile->province?->name ?? '—' }}@if($profile->city)، {{ $profile->city->name }}@endif
                </p>

                <div class="row-8 wrap">
                    @if($profile->status === \App\Enums\ProfileStatus::Pending)
                        <form method="POST" action="{{ route('admin.ambassadors.verify', $profile) }}">
                            @csrf
                            <button class="btn btn--emerald btn--sm">تأیید پروفایل</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.ambassadors.toggleStatus', $profile) }}">
                            @csrf
                            <button class="btn btn--ghost btn--sm">{{ $profile->status === \App\Enums\ProfileStatus::Active ? 'تعلیق' : 'فعال‌سازی' }}</button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.ambassadors.changeGroup', $profile) }}" class="grow" style="min-inline-size:140px">
                        @csrf
                        <select name="group_id" class="select" style="min-block-size:34px;font-size:12px" onchange="this.form.submit()">
                            <option value="">بدون سطح</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ $profile->group_id === $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </article>
        @empty
            <div class="card span-full"><x-empty-state icon="star" title="سفیری یافت نشد" tight>با فیلتر فعلی پروفایلی وجود ندارد.</x-empty-state></div>
        @endforelse
    </div>

    <div class="mt-24">{{ $profiles->links() }}</div>
@endsection
