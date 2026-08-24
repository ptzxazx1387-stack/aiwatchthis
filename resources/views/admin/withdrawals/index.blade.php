@extends('layouts.app')

@section('title', 'درخواست‌های برداشت')
@section('page-title', 'درخواست‌های برداشت')

@section('content')
    <div class="mb-4 flex items-center gap-2 flex-wrap">
        @foreach(['' => 'همه', 'pending' => 'در انتظار', 'approved' => 'تأییدشده', 'rejected' => 'ردشده'] as $key => $label)
            <a href="{{ route('admin.withdrawals.index', $key ? ['status' => $key] : []) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ (request('status') ?? '') === $key ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-right font-medium">کاربر</th>
                    <th class="px-4 py-3 text-right font-medium">مبلغ</th>
                    <th class="px-4 py-3 text-right font-medium">شماره شبا</th>
                    <th class="px-4 py-3 text-right font-medium">وضعیت</th>
                    <th class="px-4 py-3 text-right font-medium">تاریخ</th>
                    <th class="px-4 py-3 text-right font-medium">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($withdrawals as $w)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $w->user->name }}</td>
                        <td class="px-4 py-3 font-bold text-gray-800">{{ number_format($w->amount) }} تومان</td>
                        <td class="px-4 py-3 text-gray-600" dir="ltr">{{ $w->sheba_number }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs {{ $w->status->badgeClass() }}">{{ $w->status->label() }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $w->created_at->format('Y/m/d') }}</td>
                        <td class="px-4 py-3">
                            @if($w->status === \App\Enums\WithdrawalStatus::Pending)
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.withdrawals.approve', $w) }}">
                                        @csrf
                                        <button class="text-xs text-emerald-600 hover:underline font-medium">تأیید</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.withdrawals.reject', $w) }}" class="flex gap-1">
                                        @csrf
                                        <input type="text" name="review_note" placeholder="دلیل" class="text-xs rounded border-gray-300 px-2 py-1 border w-24">
                                        <button class="text-xs text-rose-600 hover:underline font-medium">رد</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">بررسی‌شده</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">درخواستی یافت نشد</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4">{{ $withdrawals->links() }}</div>
    </div>
@endsection
