<?php

namespace App\Models;

use App\Enums\ProfileStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmbassadorProfile extends Model
{
    protected $fillable = [
    'user_id', 'ig_username', 'ig_profile_url', 'category_id', 'province_id',
    'city_id', 'group_id', 'followers_count', 'bio', 'avg_views_7d',
    'status', 'is_verified', 'verified_at', 'admin_note',
];

    protected function casts(): array
    {
        return [
            'followers_count' => 'integer',
            'avg_views_7d' => 'integer',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            'status' => ProfileStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * اسکوپ فقط پروفایل‌های فعال
     */
    public function scopeActive($query)
    {
        return $query->where('status', ProfileStatus::Active->value);
    }
}
