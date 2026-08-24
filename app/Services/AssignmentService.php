<?php

namespace App\Services;

use App\Enums\AssignmentStatus;
use App\Enums\CampaignStatus;
use App\Enums\ProfileStatus;
use App\Models\AmbassadorProfile;
use App\Models\Campaign;
use App\Models\CampaignAssignment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    /**
     * تخصیص خودکار کمپین به سفیران بر اساس میانگین ویوی ۷ روز گذشته.
     *
     * مراحل:
     *  ۱) یافتن سفیران واجد شرایط (پروفایل فعال، دسته/موقعیت منطبق،
     *     میانگین ویوی بالاتر از حداقل کمپین، رعایت محدودیت سطح گروه)
     *  ۲) مرتب‌سازی بر اساس میانگین ویوی ۷ روزه (نزولی)
     *  ۳) تخصیص تا پر شدن ظرفیت باقیمانده کمپین
     *
     * @return array{assigned: int, skipped: array}
     */
    public function autoAssign(Campaign $campaign): array
    {
        if ($campaign->status !== CampaignStatus::Active) {
            return ['assigned' => 0, 'skipped' => ['کمپین فعال نیست.']];
        }

        $remaining = $campaign->remaining_capacity;
        if ($remaining <= 0) {
            return ['assigned' => 0, 'skipped' => ['ظرفیت کمپین تکمیل است.']];
        }

        $ambassadors = $this->eligibleAmbassadors($campaign);

        $assigned = 0;
        $skipped = [];

        foreach ($ambassadors as $ambassador) {
            if ($remaining <= 0) {
                break;
            }

            // بررسی ظرفیت روزانه/هفتگی گروه سفیر
            if (! $this->canReceiveNewCampaign($ambassador)) {
                $skipped[] = sprintf('سفیر %s به سقف دریافت تبلیغ (سطح گروه) رسیده است.', $ambassador->name);
                continue;
            }

            $alreadyAssigned = CampaignAssignment::where('campaign_id', $campaign->id)
                ->where('ambassador_id', $ambassador->id)
                ->exists();

            if ($alreadyAssigned) {
                $skipped[] = sprintf('سفیر %s پیش‌تر به این کمپین تخصیص یافته است.', $ambassador->name);
                continue;
            }

            DB::transaction(function () use ($campaign, $ambassador) {
                CampaignAssignment::create([
                    'campaign_id' => $campaign->id,
                    'ambassador_id' => $ambassador->id,
                    'status' => AssignmentStatus::Assigned->value,
                    'assigned_at' => now(),
                ]);

                $campaign->decrement('remaining_capacity');
            });

            $assigned++;
        }

        // اگر ظرفیت پر شد، کمپین را «تکمیل‌شده» علامت بزن
        $campaign->refresh();
        if ($campaign->remaining_capacity <= 0) {
            $campaign->update(['status' => CampaignStatus::Completed->value]);
        }

        return ['assigned' => $assigned, 'skipped' => $skipped];
    }

    /**
     * یافتن سفیران واجد شرایط برای یک کمپین، مرتب‌شده بر اساس میانگین ویو.
     */
    public function eligibleAmbassadors(Campaign $campaign)
    {
        $query = AmbassadorProfile::active()
            ->with('user')
            ->where('avg_views_7d', '>=', $campaign->min_avg_views);

        // فیلتر دسته‌بندی
        if ($campaign->category_id) {
            $query->where('category_id', $campaign->category_id);
        }

        // فیلتر جغرافیایی (استان/شهر هدف)
        if ($campaign->province_id) {
            $query->where('province_id', $campaign->province_id);
        }
        if ($campaign->city_id) {
            $query->where('city_id', $campaign->city_id);
        }

        return $query->orderByDesc('avg_views_7d')->get()
            ->map(fn ($p) => $p->user)
            ->filter();
    }

    /**
     * بررسی محدودیت دریافت تبلیغ بر اساس سطح گروه سفیر.
     */
    public function canReceiveNewCampaign(User $ambassador): bool
    {
        $profile = $ambassador->ambassadorProfile;
        $group = $profile?->group;

        if (! $group) {
            return true; // بدون گروه، محدودیتی اعمال نمی‌شود
        }

        // شمارش کمپین‌های دریافت‌شده امروز
        $dailyCount = CampaignAssignment::where('ambassador_id', $ambassador->id)
            ->whereDate('assigned_at', today())
            ->count();

        if ($dailyCount >= $group->daily_campaign_limit) {
            return false;
        }

        // شمارش کمپین‌های دریافت‌شده این هفته
        $weeklyCount = CampaignAssignment::where('ambassador_id', $ambassador->id)
            ->where('assigned_at', '>=', now()->startOfWeek())
            ->count();

        if ($weeklyCount >= $group->weekly_campaign_limit) {
            return false;
        }

        return true;
    }
}
