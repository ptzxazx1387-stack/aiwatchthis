<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * ساخت (یا بازیابی) کیف پول یک کاربر
     */
    public function ensureWallet(User $user): Wallet
    {
        return Wallet::firstOrCreate(['user_id' => $user->id]);
    }

    /**
     * واریز به کیف پول
     */
    public function credit(
        User $user,
        float $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
    ): WalletTransaction {
        return DB::transaction(function () use ($user, $amount, $description, $referenceType, $referenceId) {
            $wallet = $this->ensureWallet($user);
            $wallet->balance += $amount;
            $wallet->save();

            return $this->record($wallet, $user, TransactionType::Credit, $amount, $description, $referenceType, $referenceId);
        });
    }

    /**
     * برداشت از کیف پول
     */
    public function debit(
        User $user,
        float $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
    ): WalletTransaction {
        return DB::transaction(function () use ($user, $amount, $description, $referenceType, $referenceId) {
            $wallet = $this->ensureWallet($user);

            if ($wallet->balance < $amount) {
                throw new \InvalidArgumentException('موجودی کیف پول کافی نیست.');
            }

            $wallet->balance -= $amount;
            $wallet->save();

            return $this->record($wallet, $user, TransactionType::Debit, $amount, $description, $referenceType, $referenceId);
        });
    }

    /**
     * ثبت تراکنش
     */
    protected function record(
        Wallet $wallet,
        User $user,
        TransactionType $type,
        float $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
    ): WalletTransaction {
        return WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'type' => $type->value,
            'amount' => $amount,
            'balance_after' => $wallet->balance,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
            'status' => 'completed',
        ]);
    }

    /**
     * محاسبه درآمد سفیر از یک ثبت‌ویوی تأییدشده:
     * درآمد = تعداد ویو × قیمت هر ویو × (۱ − نرخ کمیسیون)
     */
    public function calculateEarning(int $viewsCount, float $pricePerView, ?float $commissionRate = null): float
    {
        $rate = $commissionRate ?? (float) Setting::get('commission_rate', 0);

        return round($viewsCount * $pricePerView * (1 - $rate / 100), 0);
    }
}
