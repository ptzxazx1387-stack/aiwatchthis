<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Campaign extends Model
{
    protected $fillable = [
    'advertiser_id', 'title', 'slug', 'description', 'story_content',
    'category_id', 'province_id', 'city_id', 'price_per_view', 'commission_rate',
    'capacity', 'remaining_capacity', 'min_avg_views', 'max_assignments_per_ambassador',
    'status', 'start_date', 'end_date', 'admin_note',
];

    protected function casts(): array
    {
        return [
            'price_per_view' => 'integer',
            'commission_rate' => 'float',
            'capacity' => 'integer',
            'remaining_capacity' => 'integer',
            'min_avg_views' => 'integer',
            'max_assignments_per_ambassador' => 'integer',
            'status' => CampaignStatus::class,
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Campaign $campaign) {
            // تولید خودکار اسلاگ یکتا
            if (empty($campaign->slug)) {
                $campaign->slug = self::uniqueSlug($campaign->title);
            }
            // مقداردهی اولیه ظرفیت باقیمانده
            if (empty($campaign->remaining_capacity) && ! empty($campaign->capacity)) {
                $campaign->remaining_capacity = $campaign->capacity;
            }
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title, '-');
        $original = $slug;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advertiser_id');
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

    public function assignments(): HasMany
    {
        return $this->hasMany(CampaignAssignment::class);
    }

    public function viewSubmissions(): HasMany
    {
        return $this->hasMany(ViewSubmission::class);
    }

    /* ------------------------------------------------------------------ */
    /*  Scopes                                                             */
    /* ------------------------------------------------------------------ */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', CampaignStatus::Active->value);
    }

    /**
     * فیلتر بر اساس استان، شهر و دسته‌بندی
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['province_id'] ?? null, fn ($q, $v) => $q->where('province_id', $v))
            ->when($filters['city_id'] ?? null, fn ($q, $v) => $q->where('city_id', $v))
            ->when($filters['category_id'] ?? null, fn ($q, $v) => $q->where('category_id', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v));
    }
}
