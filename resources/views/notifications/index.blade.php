@extends('layouts.app')

@section('title', 'اعلان‌ها')

@section('content')
    <x-page-header eyebrow="Notifications" title="اعلان‌ها" lead="پیام‌ها، تغییر وضعیت‌ها و کارهایی که نیاز به توجه دارند این‌جا جمع می‌شوند.">
        <x-slot name="actions">
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button class="btn btn--ghost btn--sm">علامت‌گذاری همه به‌عنوان خوانده‌شده</button>
            </form>
        </x-slot>
    </x-page-header>

    <div class="stack-12 medium">
        @forelse($notifications as $notification)
            @php
                $tone = ['success' => 'emerald', 'warning' => 'amber', 'danger' => 'rose'][$notification->type] ?? 'mist';
                $icon = ['success' => 'check-circle', 'warning' => 'alert', 'danger' => 'x-circle'][$notification->type] ?? 'info';
            @endphp
            <article class="card row-top" data-reveal style="padding:18px 20px">
                <span class="avatar avatar--sm avatar--mist"><x-icon :name="$icon" :size="15" /></span>
                <div class="grow stack-6">
                    <div class="row between wrap" style="gap:10px">
                        <h2 class="h4">{{ $notification->title }}</h2>
                        @if(is_null($notification->read_at))<x-badge tone="amber" bare>جدید</x-badge>@endif
                    </div>
                    @if($notification->body)<p class="body">{{ $notification->body }}</p>@endif
                    <p class="micro figure">{{ \App\Support\Fmt::dateTime($notification->created_at) }}</p>
                </div>
                @if(is_null($notification->read_at))
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf
                        <button class="btn btn--quiet btn--xs">خواندم</button>
                    </form>
                @endif
            </article>
        @empty
            <div class="card">
                <x-empty-state icon="bell" title="اعلانی ندارید" tight>وقتی رویداد جدیدی اتفاق بیفتد، اینجا نمایش داده می‌شود.</x-empty-state>
            </div>
        @endforelse
    </div>

    <div class="mt-24">{{ $notifications->links() }}</div>
@endsection
