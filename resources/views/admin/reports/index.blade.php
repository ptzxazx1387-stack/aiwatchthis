@extends('layouts.app')

@section('title', 'گزارش‌های مالی و آماری')
@section('page-title', 'گزارش‌های مالی و آماری')

@section('content')
    <form method="GET" class="mb-6 bg-white rounded-2xl border border-gray-200 p-4 flex items-end gap-3 flex-wrap">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">از تاریخ</label>
            <input type="date" name="from" value="{{ $from }}" class="text-sm rounded-lg border-gray-300 px-3 py-2 border">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">تا تاریخ</label>
            <input type="date" name="to" value="{{ $to }}" class="text-sm rounded-lg border-gray-300 px-3 py-2 border">
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium">اعمال فیلتر</button>
    </form>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="مجموع واریزی‌ها" :value="$financial['total_credits']" icon="📥" color="emerald" />
        <x-stat-card label="مجموع برداشت‌ها" :value="$financial['total_debits']" icon="📤" color="rose" />
        <x-stat-card label="کمیسیون سامانه" :value="$financial['total_commission']" icon="🏦" color="violet" />
        <x-stat-card label="کل ویوهای تأییدشده" :value="$activity['total_approved_views']" icon="👁️" color="sky" />
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">تراکنش‌های اخیر</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-right font-medium">کاربر</th>
                        <th class="px-4 py-3 text-right font-medium">نوع</th>
                        <th class="px-4 py-3 text-right font-medium">مبلغ</th>
                        <th class="px-4 py-3 text-right font-medium">موجودی پس از تراکنش</th>
                        <th class="px-4 py-3 text-right font-medium">توضیحات</th>
                        <th class="px-4 py-3 text-right font-medium">تاریخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $tx->user->name }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs {{ $tx->type->value === 'credit' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $tx->type->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-bold {{ $tx->type->value === 'credit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $tx->type->value === 'credit' ? '+' : '-' }}{{ number_format($tx->amount) }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ number_format($tx->balance_after) }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $tx->description }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $tx->created_at->format('Y/m/d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">تراکنشی یافت نشد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
