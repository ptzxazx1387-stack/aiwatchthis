<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    /**
     * تبلیغ‌دهنده فقط کمپین خودش را می‌بیند؛ مدیر همه را.
     */
    public function view(User $user, Campaign $campaign): bool
    {
        return $user->isAdmin() || $campaign->advertiser_id === $user->id;
    }

    public function update(User $user, Campaign $campaign): bool
    {
        return $user->isAdmin() || $campaign->advertiser_id === $user->id;
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->isAdmin() || $campaign->advertiser_id === $user->id;
    }
}
