@extends('layouts.app')

@section('title', 'مدیریت سفیران')
@section('page-title', 'مدیریت سفیران')

@section('content')
    <div class="mb-4 flex items-center gap-2 flex-wrap">
        @foreach(['' => 'همه', 'pending' => 'در انتظار تأیید', 'active' => 'فعال', 'suspended' => 'معلق'] as $key => $label)
            <a href="{{ route('admin.ambassadors.index', $key ? ['status' => $key] : []) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ (request('status') ?? '') === $key ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($profiles as $profile)
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                            {{ mb_substr($profile->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">{{ $profile->user->name }}</div>
                            <div class="text-xs text-gray-500" dir="ltr">@{{ $profile->ig_username }}</div>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs {{ $profile->status->badgeClass() }}">{{ $profile->status->label() }}</span>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="bg-gray-50 rounded-lg p-2">
                        <div class="font-bold text-gray-800">{{ number_format($profile->followers_count) }}</div>
                        <div class="text-gray-500">دنبال‌کننده</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2">
                        <div class="font-bold text-gray-800">{{ number_format($profile->avg_views_7d) }}</div>
                        <div class="text-gray-500">میانگین ویو ۷ روز</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2">
                        <div class="font-bold text-gray-800">{{ $profile->group?->name ?? '—' }}</div>
                        <div class="text-gray-500">سطح</div>
                    </div>
                </div>

                <div class="mt-3 text-xs text-gray-500 space-y-0.5">
                    <div>دسته: {{ $profile->category?->name ?? '—' }}</div>
                    <div>موقعیت: {{ $profile->province?->name ?? '—' }}، {{ $profile->city?->name ?? '—' }}</div>
                </div>

                <div class="mt-4 flex items-center gap-2 flex-wrap">
                    @if($profile->status === \App\Enums\ProfileStatus::Pending)
                        <form method="POST" action="{{ route('admin.ambassadors.verify', $profile) }}">
                            @csrf
                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-3 py-1.5 text-xs font-medium">✅ تأیید پروفایل</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.ambassadors.toggleStatus', $profile) }}">
                            @csrf
                            <button class="{{ $profile->status === \App\Enums\ProfileStatus::Active ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }} rounded-lg px-3 py-1.5 text-xs font-medium">
                                {{ $profile->status === \App\Enums\ProfileStatus::Active ? 'تعلیق' : 'فعال‌سازی' }}
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.ambassadors.changeGroup', $profile) }}" class="flex items-center gap-1">
                        @csrf
                        <select name="group_id" class="text-xs rounded border-gray-300 px-1.5 py-1.5 border" onchange="this.form.submit()">
                            <option value="">بدون سطح</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ $profile->group_id === $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-2xl border border-gray-200 p-10 text-center text-gray-400">سفیری یافت نشد</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $profiles->links() }}</div>
@endsection
