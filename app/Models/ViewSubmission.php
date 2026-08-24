<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewSubmission extends Model
{
    protected $fillable = [
    'assignment_id', 'campaign_id', 'ambassador_id', 'views_count',
    'screenshot_path', 'description', 'status', 'reviewed_by',
    'review_note', 'submitted_at', 'reviewed_at',
];

    protected function casts(): array
    {
        return [
            'views_count' => 'integer',
            'status' => SubmissionStatus::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(CampaignAssignment::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function ambassador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ambassador_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', SubmissionStatus::Pending->value);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', SubmissionStatus::Approved->value);
    }
}
