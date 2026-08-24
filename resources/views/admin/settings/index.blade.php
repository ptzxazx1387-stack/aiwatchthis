@extends('layouts.app')

@section('title', 'تنظیمات سامانه')
@section('page-title', 'تنظیمات سامانه')

@section('content')
    <div class="max-w-xl bg-white rounded-2xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نام سامانه</label>
                <input type="text" name="site_name" value="{{ \App\Models\Setting::get('site_name') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 text-sm px-3 py-2.5 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نرخ کمیسیون سامانه (٪)</label>
                <input type="number" step="0.1" name="commission_rate" value="{{ \App\Models\Setting::get('commission_rate', 10) }}"
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 text-sm px-3 py-2.5 border">
                <p class="text-xs text-gray-400 mt-1">این درصد از درآمد هر سفیر به‌عنوان کمیسیون کسر می‌شود.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">حداقل مبلغ برداشت (تومان)</label>
                <input type="number" name="min_withdrawal_amount" value="{{ \App\Models\Setting::get('min_withdrawal_amount', 100000) }}"
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 text-sm px-3 py-2.5 border">
            </div>

            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg px-6 py-2.5 transition">ذخیره تنظیمات</button>
        </form>
    </div>
@endsection
