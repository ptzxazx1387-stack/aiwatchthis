<?php

namespace App\Policies;

use App\Models\CampaignAssignment;
use App\Models\User;

class CampaignAssignmentPolicy
{
    /**
     * سفیر فقط می‌تواند تخصیص خودش را مدیریت کند؛ مدیر همه را.
     */
    public function manage(User $user, CampaignAssignment $assignment): bool
    {
        return $user->isAdmin() || $assignment->ambassador_id === $user->id;
    }
}
