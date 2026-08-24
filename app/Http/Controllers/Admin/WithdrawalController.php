<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionType;
use App\Enums\WithdrawalStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\WithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * فهرست درخواست‌های برداشت
     */
    public function index(Request $request): View
    {
        $query = WithdrawalRequest::with('user');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $withdrawals = $query->latest()->paginate(20)->withQueryString();

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * تأیید برداشت (کسر از کیف پول)
     */
    public function approve(WithdrawalRequest $withdrawal, WalletService $wallet): RedirectResponse
    {
        if ($withdrawal->status !== WithdrawalStatus::Pending) {
            return back()->with('error', 'این درخواست قبلاً بررسی شده است.');
        }

        $wallet->debit(
            $withdrawal->user,
            $withdrawal->amount,
            'برداشت تأییدشده از کیف پول',
            'withdrawal',
            $withdrawal->id,
        );

        $withdrawal->update([
            'status' => WithdrawalStatus::Approved->value,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        ActivityLog::record(auth()->id(), 'withdrawal.approved', "تأیید برداشت #{$withdrawal->id}", $withdrawal);

        Notification::send(
            $withdrawal->user_id,
            'درخواست برداشت تأیید شد',
            "مبلغ " . number_format($withdrawal->amount) . ' تومان از کیف پول شما کسر و برداشت شد.',
            'success',
        );

        return back()->with('success', 'برداشت تأیید شد.');
    }

    /**
     * رد برداشت
     */
    public function reject(Request $request, WithdrawalRequest $withdrawal): RedirectResponse
    {
        $request->validate(['review_note' => ['nullable', 'string', 'max:1000']]);

        $withdrawal->update([
            'status' => WithdrawalStatus::Rejected->value,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_note' => $request->input('review_note'),
        ]);

        ActivityLog::record(auth()->id(), 'withdrawal.rejected', "رد برداشت #{$withdrawal->id}", $withdrawal);

        Notification::send(
            $withdrawal->user_id,
            'درخواست برداشت رد شد',
            $request->input('review_note') ?: 'درخواست برداشت شما رد شد.',
            'danger',
        );

        return back()->with('success', 'درخواست برداشت رد شد.');
    }
}
