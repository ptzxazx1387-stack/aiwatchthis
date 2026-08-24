<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
    'wallet_id', 'user_id', 'type', 'amount', 'balance_after',
    'reference_type', 'reference_id', 'description', 'status',
];

    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'amount' => 'float',
            'balance_after' => 'float',
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
