<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
    'name', 'code', 'daily_campaign_limit', 'weekly_campaign_limit',
    'min_avg_views', 'description', 'is_active',
];

    protected function casts(): array
    {
        return [
            'daily_campaign_limit' => 'integer',
            'weekly_campaign_limit' => 'integer',
            'min_avg_views' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function ambassadorProfiles(): HasMany
    {
        return $this->hasMany(AmbassadorProfile::class);
    }
}
