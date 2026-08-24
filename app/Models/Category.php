<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'is_active'];

    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
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
