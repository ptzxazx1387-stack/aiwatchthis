<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = ['province_id', 'name', 'is_active'];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function ambassadorProfiles(): HasMany
    {
        return $this->hasMany(AmbassadorProfile::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }
}
