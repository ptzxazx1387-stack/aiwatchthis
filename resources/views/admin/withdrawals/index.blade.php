@extends('layouts.app')

@section('title', 'درخواست‌های برداشت')

@section('content')
    <x-page-header eyebrow="Withdrawals" title="درخواست‌های برداشت" lead="هر برداشت قبل از انجام پرداخت بررسی می‌شود. دلیل رد برای کاربر قابل مشاهده است." />

    <x-filter-chips
        route="admin.withdrawals.index"
        param="status"
        :current="request('status')"
        :options="['' => 'همه', 'pending' => 'در انتظار', 'approved' => 'تأییدشده', 'rejected' => 'ردشده']"
        class="mb-24" />

    <section class="card card--flush" data-reveal>
        <div class="tablewrap">
            <table class="table">
                <thead>
                <tr><th>کاربر</th><th>مبلغ</th><th>شماره شبا</th><th>وضعیت</th><th>تاریخ</th><th>عملیات</th></tr>
                </thead>
                <tbody>
                @forelse($withdrawals as $w)
                    <tr>
                        <td>
                            <div class="row-8"><x-avatar :name="$w->user->name" size="sm" tone="mist" /><span class="strong">{{ $w->user->name }}</span></div>
                        </td>
                        <td class="num strong">@money($w->amount)</td>
                        <td class="num ltr">{{ $w->sheba_number }}</td>
                        <td><x-badge :tone="$w->status->tone()">{{ $w->status->label() }}</x-badge></td>
                        <td class="num dim">@jshort($w->created_at)</td>
                        <td>
                            @if($w->status === \App\Enums\WithdrawalStatus::Pending)
                                <div class="row-6 wrap">
                                    <form method="POST" action="{{ route('admin.withdrawals.approve', $w) }}">
                                        @csrf
                                        <button class="btn btn--emerald btn--xs">تأیید</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.withdrawals.reject', $w) }}" class="row-6" data-reason-form data-reason-required="true" data-reason-tone="rose" data-reason-title="رد برداشت" data-reason-label="دلیل رد برای کاربر" data-reason-ok="ثبت رد">
                                        @csrf
                                        <input type="hidden" name="review_note" value="" data-reason-field>
                                        <button class="btn btn--ghost btn--xs">رد</button>
                                    </form>
                                </div>
                            @else
                                <span class="micro">بررسی‌شده</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-empty-state icon="banknote" title="درخواستی یافت نشد" tight /></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card__foot">{{ $withdrawals->links() }}</div>
    </section>
@endsection
