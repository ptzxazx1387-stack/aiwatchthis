<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\WithdrawalRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * نمایش کیف پول و تراکنش‌های کاربر
     */
    public function index(): View
    {
        $wallet = auth()->user()->wallet;
        $transactions = auth()->user()
            ->walletTransactions()
            ->latest()
            ->paginate(15);

        $withdrawals = WithdrawalRequest::where('user_id', auth()->id())
            ->latest()
            ->get();

        $minWithdrawal = (int) Setting::get('min_withdrawal_amount', 100000);

        return view('wallet.index', compact('wallet', 'transactions', 'withdrawals', 'minWithdrawal'));
    }

    /**
     * ثبت درخواست برداشت
     */
    public function requestWithdrawal(Request $request): RedirectResponse
    {
        $minWithdrawal = (int) Setting::get('min_withdrawal_amount', 100000);

        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:' . $minWithdrawal],
            'sheba_number' => ['required', 'string', 'size:26'],
            'card_number' => ['nullable', 'string', 'size:16'],
        ]);

        $wallet = auth()->user()->wallet;

        if (! $wallet || $wallet->balance < $data['amount']) {
            return back()->with('error', 'موجودی کیف پول شما کافی نیست.');
        }

        WithdrawalRequest::create([
            'user_id' => auth()->id(),
            'amount' => $data['amount'],
            'sheba_number' => $data['sheba_number'],
            'card_number' => $data['card_number'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'درخواست برداشت ثبت شد و در انتظار بررسی مدیر است.');
    }
}
