@extends('layouts.app')

@section('title', 'اعلان‌ها')
@section('page-title', 'اعلان‌ها')

@section('content')
    <div class="mb-4 flex justify-end">
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button class="text-sm text-indigo-600 hover:underline">علامت‌گذاری همه به‌عنوان خوانده‌شده</button>
        </form>
    </div>

    <div class="space-y-3 max-w-2xl">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-2xl border border-gray-200 p-4 flex items-start gap-3 {{ is_null($notification->read_at) ? 'border-indigo-300 bg-indigo-50/40' : '' }}">
                <div class="text-xl mt-0.5">
                    @php $icons = ['info' => 'ℹ️', 'success' => '✅', 'warning' => '⚠️', 'danger' => '❌']; @endphp
                    {{ $icons[$notification->type] ?? 'ℹ️' }}
                </div>
                <div class="flex-1">
                    <div class="font-medium text-gray-800 text-sm">{{ $notification->title }}</div>
                    @if($notification->body)
                        <div class="text-sm text-gray-600 mt-0.5">{{ $notification->body }}</div>
                    @endif
                    <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->format('Y/m/d H:i') }}</div>
                </div>
                @if(is_null($notification->read_at))
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf
                        <button class="text-xs text-indigo-600 hover:underline whitespace-nowrap">خواندم</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-gray-400">اعلانی ندارید</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
