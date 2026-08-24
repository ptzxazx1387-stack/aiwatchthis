@extends('layouts.app')

@section('title', 'گزارش‌ها')

@section('content')
    <x-page-header eyebrow="Reports" title="گزارش‌های مالی و آماری" lead="بازه را انتخاب کنید تا تراکنش‌ها، کمیسیون و ویوهای تأییدشده سریع دیده شوند." />

    <form method="GET" class="toolbar mb-24" data-reveal>
        <x-field label="از تاریخ"><input type="date" name="from" value="{{ $from }}" class="input figure"></x-field>
        <x-field label="تا تاریخ"><input type="date" name="to" value="{{ $to }}" class="input figure"></x-field>
        <button class="btn btn--sm">اعمال بازه</button>
    </form>

    <div class="grid grid-4 keep mb-24">
        <x-stat-card label="مجموع واریزی‌ها" :value="$financial['total_credits']" icon="banknote" unit="تومان" />
        <x-stat-card label="مجموع برداشت‌ها" :value="$financial['total_debits']" icon="wallet" unit="تومان" />
        <x-stat-card label="کمیسیون سامانه" :value="$financial['total_commission']" icon="chart" unit="تومان" />
        <x-stat-card label="ویوهای تأییدشده" :value="$activity['total_approved_views']" icon="eye" />
    </div>

    <section class="card card--flush" data-reveal>
        <x-section-head title="تراکنش‌های اخیر" />
        <div class="tablewrap">
            <table class="table">
                <thead><tr><th>کاربر</th><th>نوع</th><th>مبلغ</th><th>موجودی پس از تراکنش</th><th>توضیحات</th><th>تاریخ</th></tr></thead>
                <tbody>
                @forelse($recentTransactions as $tx)
                    <tr>
                        <td class="strong">{{ $tx->user->name }}</td>
                        <td><x-badge :tone="$tx->type->value === 'credit' ? 'emerald' : 'rose'">{{ $tx->type->label() }}</x-badge></td>
                        <td class="num strong {{ $tx->type->value === 'credit' ? 'tone-emerald' : 'tone-rose' }}">{{ $tx->type->value === 'credit' ? '+' : '−' }}@fa($tx->amount)</td>
                        <td class="num">@fa($tx->balance_after)</td>
                        <td class="dim">{{ $tx->description }}</td>
                        <td class="num dim">@jdatetime($tx->created_at)</td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-empty-state icon="chart" title="تراکنشی یافت نشد" tight /></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
