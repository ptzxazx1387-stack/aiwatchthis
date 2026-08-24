<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = ['name', 'code', 'is_active'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
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
