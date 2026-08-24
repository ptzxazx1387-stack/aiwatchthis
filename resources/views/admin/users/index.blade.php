@extends('layouts.app')

@section('title', 'مدیریت کاربران')
@section('page-title', 'مدیریت کاربران')

@section('content')
    <div class="mb-4 flex items-center gap-2 flex-wrap">
        @foreach(['' => 'همه', 'admin' => 'مدیران', 'advertiser' => 'تبلیغ‌دهندگان', 'ambassador' => 'سفیران'] as $key => $label)
            <a href="{{ route('admin.users.index', $key ? ['role' => $key] : []) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ request('role') === $key || (!request('role') && $key === '') ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-right font-medium">کاربر</th>
                    <th class="px-4 py-3 text-right font-medium">نقش</th>
                    <th class="px-4 py-3 text-right font-medium">وضعیت</th>
                    <th class="px-4 py-3 text-right font-medium">موجودی</th>
                    <th class="px-4 py-3 text-right font-medium">تاریخ ثبت</th>
                    <th class="px-4 py-3 text-right font-medium">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs bg-indigo-50 text-indigo-700">{{ $user->role->label() }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $user->status === 'active' ? 'فعال' : 'معلق' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ number_format($user->wallet?->balance ?? 0) }} تومان</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $user->created_at->format('Y/m/d') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.users.toggleStatus', $user) }}">
                                    @csrf
                                    <button class="text-xs {{ $user->status === 'active' ? 'text-rose-600' : 'text-emerald-600' }} hover:underline">
                                        {{ $user->status === 'active' ? 'تعلیق' : 'فعال‌سازی' }}
                                    </button>
                                </form>
                                @if(!$user->isAdmin() || auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.changeRole', $user) }}" class="flex items-center gap-1">
                                        @csrf
                                        <select name="role" class="text-xs rounded border-gray-300 px-1.5 py-1 border" onchange="this.form.submit()">
                                            @foreach(\App\Enums\UserRole::options() as $key => $label)
                                                <option value="{{ $key }}" {{ $user->role->value === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">کاربری یافت نشد</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4">{{ $users->links() }}</div>
    </div>
@endsection
