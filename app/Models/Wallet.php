<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance', 'blocked_balance', 'currency'];

    protected function casts(): array
    {
        return [
            'balance' => 'float',
            'blocked_balance' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * موجودی کل (قابل برداشت + مسدود)
     */
    public function getTotalBalanceAttribute(): float
    {
        return $this->balance + $this->blocked_balance;
    }
}
