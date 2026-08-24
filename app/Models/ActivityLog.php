<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
    'user_id', 'action', 'subject_type', 'subject_id', 'description', 'properties', 'ip_address',
];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ثبت سریع یک فعالیت
     */
    public static function record(
        ?int $userId,
        string $action,
        ?string $description = null,
        ?Model $subject = null,
        array $properties = [],
        ?string $ip = null,
    ): self {
        return static::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->id,
            'properties' => $properties,
            'ip_address' => $ip ?? request()->ip(),
        ]);
    }
}
