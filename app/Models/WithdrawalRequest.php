<?php

namespace App\Models;

use App\Enums\WithdrawalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    protected $fillable = [
    'user_id', 'amount', 'status', 'sheba_number', 'card_number',
    'reviewed_by', 'review_note', 'reviewed_at',
];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'status' => WithdrawalStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', WithdrawalStatus::Pending->value);
    }
}
