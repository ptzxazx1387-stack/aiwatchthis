@extends('layouts.app')

@section('title', 'کیف پول')
@section('page-title', 'کیف پول')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gradient-to-l from-indigo-600 to-violet-600 rounded-2xl p-6 text-white">
                <div class="text-sm text-indigo-100 mb-1">موجودی قابل برداشت</div>
                <div class="text-3xl font-bold">{{ number_format($wallet?->balance ?? 0) }} <span class="text-base font-normal">تومان</span></div>
                <div class="mt-3 text-xs text-indigo-200">موجودی مسدود: {{ number_format($wallet?->blocked_balance ?? 0) }} تومان</div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">تراکنش‌ها</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($transactions as $tx)
                        <div class="px-5 py-3 flex items-center justify-between text-sm">
                            <div>
                                <div class="font-medium text-gray-800">{{ $tx->description }}</div>
                                <div class="text-xs text-gray-400">{{ $tx->created_at->format('Y/m/d H:i') }}</div>
                            </div>
                            <span class="font-bold {{ $tx->type->value === 'credit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $tx->type->value === 'credit' ? '+' : '-' }}{{ number_format($tx->amount) }}
                            </span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-400 text-sm">تراکنشی ثبت نشده است</div>
                    @endforelse
                </div>
                <div class="px-5 py-4">{{ $transactions->links() }}</div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4">درخواست برداشت</h3>
                <form method="POST" action="{{ route('wallet.withdraw') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">مبلغ (تومان)</label>
                        <input type="number" name="amount" required min="{{ $minWithdrawal }}"
                               class="w-full text-sm rounded-lg border-gray-300 px-3 py-2 border">
                        <p class="text-xs text-gray-400 mt-1">حداقل مبلغ برداشت: {{ number_format($minWithdrawal) }} تومان</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">شماره شبا</label>
                        <input type="text" name="sheba_number" required dir="ltr" placeholder="IR..."
                               class="w-full text-sm rounded-lg border-gray-300 px-3 py-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">شماره کارت (اختیاری)</label>
                        <input type="text" name="card_number" dir="ltr" placeholder="0000-0000-0000-0000"
                               class="w-full text-sm rounded-lg border-gray-300 px-3 py-2 border">
                    </div>
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg py-2 text-sm font-medium">ثبت درخواست</button>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">درخواست‌های برداشت</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($withdrawals as $w)
                        <div class="px-5 py-3 flex items-center justify-between text-sm">
                            <div>
                                <div class="font-medium text-gray-800">{{ number_format($w->amount) }} تومان</div>
                                <div class="text-xs text-gray-400">{{ $w->created_at->format('Y/m/d') }}</div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs {{ $w->status->badgeClass() }}">{{ $w->status->label() }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-center text-gray-400 text-sm">درخواستی ثبت نشده است</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
