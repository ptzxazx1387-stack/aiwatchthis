<?php

namespace App\Services;

use App\Models\User;
use App\Models\ViewSubmission;
use App\Enums\SubmissionStatus;

class AmbassadorStatsService
{
    /**
     * محاسبه میانگین ویوی ۷ روز گذشته یک سفیر بر اساس ثبت‌ویوهای تأییدشده.
     *
     * منطق: مجموع ویوهای تأییدشده در ۷ روز گذشته تقسیم بر ۷.
     */
    public function avgViewsLast7Days(User $ambassador): float
    {
        $total = ViewSubmission::where('ambassador_id', $ambassador->id)
            ->where('status', SubmissionStatus::Approved->value)
            ->where('submitted_at', '>=', now()->subDays(7))
            ->sum('views_count');

        return round($total / 7, 1);
    }

    /**
     * مجموع ویوهای تأییدشده در ۷ روز گذشته
     */
    public function totalViewsLast7Days(User $ambassador): int
    {
        return (int) ViewSubmission::where('ambassador_id', $ambassador->id)
            ->where('status', SubmissionStatus::Approved->value)
            ->where('submitted_at', '>=', now()->subDays(7))
            ->sum('views_count');
    }

    /**
     * به‌روزرسانی کش میانگین ویوی ۷ روزه در پروفایل سفیر.
     * این مقدار مبنای تخصیص خودکار کمپین‌هاست.
     */
    public function refreshAvgViews(User $ambassador): ?float
    {
        $profile = $ambassador->ambassadorProfile;
        if (! $profile) {
            return null;
        }

        $avg = $this->avgViewsLast7Days($ambassador);
        $profile->update(['avg_views_7d' => (int) $avg]);

        return $avg;
    }
}
