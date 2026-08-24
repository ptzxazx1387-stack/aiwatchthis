@extends('layouts.app')

@section('title', 'تنظیمات سامانه')

@section('content')
    <x-page-header eyebrow="Settings" title="تنظیمات سامانه" lead="تنها چند مورد سراسری اینجا تنظیم می‌شود؛ بقیه صفحه‌ها خودشان مستقل و ساده نگه داشته شده‌اند." />

    <form method="POST" action="{{ route('admin.settings.update') }}" class="card narrow stack-20" data-reveal>
        @csrf
        <x-field label="نام سامانه" name="site_name" required>
            <input type="text" name="site_name" value="{{ old('site_name', \App\Models\Setting::get('site_name')) }}" class="input" required>
        </x-field>

        <x-field label="نرخ کمیسیون سامانه (درصد)" name="commission_rate" required hint="این درصد از درآمد هر سفیر به‌عنوان کارمزد کسر می‌شود.">
            <input type="number" step="0.1" name="commission_rate" value="{{ old('commission_rate', \App\Models\Setting::get('commission_rate', 10)) }}" class="input figure" required>
        </x-field>

        <x-field label="حداقل مبلغ برداشت (تومان)" name="min_withdrawal_amount" required>
            <input type="number" name="min_withdrawal_amount" value="{{ old('min_withdrawal_amount', \App\Models\Setting::get('min_withdrawal_amount', 100000)) }}" class="input figure" required>
        </x-field>

        <button class="btn">ذخیره تنظیمات</button>
    </form>
@endsection
