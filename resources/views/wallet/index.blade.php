@extends('layouts.app')

@section('title', 'کیف پول')

@section('content')
    <x-page-header eyebrow="Wallet" title="کیف پول" lead="موجودی، تراکنش‌ها و درخواست‌های برداشت در یک نمای ساده و قابل‌پیگیری." />

    <div class="grid grid-side grid-24">
        <div class="stack-24">
            <section class="ledger" data-reveal>
                <div class="row between wrap" style="gap:18px">
                    <div>
                        <span class="eyebrow">Available balance</span>
                        <div class="ledger__value">
                            <span class="figure">{{ \App\Support\Fmt::num($wallet?->balance ?? 0) }}</span>
                            <span class="ledger__unit">تومان</span>
                        </div>
                        <p class="ledger__side">
                            موجودی مسدود:
                            <span class="figure">{{ \App\Support\Fmt::num($wallet?->blocked_balance ?? 0) }}</span>
                            تومان
                        </p>
                    </div>
                    <x-icon name="wallet" :size="34" class="dim" style="color:rgba(255,255,255,.55)" />
                </div>
            </section>

            <section class="card card--flush" data-reveal>
                <x-section-head title="تراکنش‌ها" />
                <div class="list">
                    @forelse($transactions as $tx)
                        <div class="list__row">
                            <span class="avatar avatar--sm avatar--mist">
                                <x-icon :name="$tx->type->value === 'credit' ? 'banknote' : 'wallet'" :size="14" />
                            </span>
                            <div class="grow stack-4">
                                <span class="strong truncate">{{ $tx->description }}</span>
                                <span class="micro figure">{{ \App\Support\Fmt::dateTime($tx->created_at) }}</span>
                            </div>
                            <span class="h4 figure {{ $tx->type->value === 'credit' ? 'tone-emerald' : 'tone-rose' }}">
                                {{ $tx->type->value === 'credit' ? '+' : '−' }}{{ \App\Support\Fmt::money($tx->amount) }}
                            </span>
                        </div>
                    @empty
                        <x-empty-state icon="wallet" title="تراکنشی ثبت نشده است">هنوز واریز، برداشت یا درآمدی در کیف پول شما ثبت نشده.</x-empty-state>
                    @endforelse
                </div>
                @if($transactions->hasPages())
                    <div class="card__foot">{{ $transactions->links() }}</div>
                @endif
            </section>
        </div>

        <aside class="stack-24">
            <section class="card" data-reveal>
                <span class="eyebrow">Withdraw</span>
                <h2 class="h3 mt-8">درخواست برداشت</h2>
                <form method="POST" action="{{ route('wallet.withdraw') }}" class="stack-16 mt-24">
                    @csrf
                    <x-field label="مبلغ (تومان)" name="amount" required hint="حداقل مبلغ برداشت: {{ \App\Support\Fmt::money($minWithdrawal) }}">
                        <input type="number" name="amount" required min="{{ $minWithdrawal }}" class="input figure @error('amount') input--err @enderror" placeholder="مثال: ۵۰۰٬۰۰۰">
                    </x-field>
                    <x-field label="شماره شبا" name="sheba_number" required hint="بدون خط تیره و با IR شروع شود.">
                        <input type="text" name="sheba_number" required dir="ltr" value="{{ old('sheba_number') }}" class="input ltr @error('sheba_number') input--err @enderror" placeholder="IR000000000000000000000000">
                    </x-field>
                    <x-field label="شماره کارت (اختیاری)" name="card_number" optional>
                        <input type="text" name="card_number" dir="ltr" value="{{ old('card_number') }}" class="input ltr" placeholder="0000-0000-0000-0000">
                    </x-field>
                    <button type="submit" class="btn btn--block">ثبت درخواست برداشت</button>
                </form>
            </section>

            <section class="card card--flush" data-reveal>
                <x-section-head title="درخواست‌های برداشت" />
                <div class="list">
                    @forelse($withdrawals as $w)
                        <div class="list__row">
                            <div class="grow stack-4">
                                <span class="strong figure">{{ \App\Support\Fmt::money($w->amount) }}</span>
                                <span class="micro figure">{{ \App\Support\Fmt::date($w->created_at) }}</span>
                            </div>
                            <x-badge :tone="$w->status->tone()">{{ $w->status->label() }}</x-badge>
                        </div>
                    @empty
                        <x-empty-state icon="banknote" title="درخواستی ثبت نشده است" tight />
                    @endforelse
                </div>
            </section>
        </aside>
    </div>
@endsection
