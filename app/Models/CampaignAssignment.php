<?php

namespace App\Models;

use App\Enums\AssignmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignAssignment extends Model
{
    protected $fillable = [
    'campaign_id', 'ambassador_id', 'status', 'assigned_at',
    'accepted_at', 'completed_at', 'decline_reason',
];

    protected function casts(): array
    {
        return [
            'status' => AssignmentStatus::class,
            'assigned_at' => 'datetime',
            'accepted_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function ambassador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ambassador_id');
    }

    public function viewSubmissions(): HasMany
    {
        return $this->hasMany(ViewSubmission::class);
    }

    /**
     * آخرین ثبت ویو این تخصیص
     */
    public function latestSubmission()
    {
        return $this->viewSubmissions()->latest()->first();
    }
}
