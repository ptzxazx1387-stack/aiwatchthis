<?php

namespace App\Http\Controllers\Advertiser;

use App\Enums\CampaignStatus;
use App\Enums\SubmissionStatus;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * داشبورد تبلیغ‌دهنده
     */
    public function index(): View
    {
        $user = auth()->user();

        $stats = [
            'total_campaigns' => $user->campaigns()->count(),
            'active_campaigns' => $user->campaigns()->where('status', CampaignStatus::Active->value)->count(),
            'pending_campaigns' => $user->campaigns()->where('status', CampaignStatus::Pending->value)->count(),
            'total_approved_views' => (int) $user->campaigns()
                ->join('view_submissions', 'campaigns.id', '=', 'view_submissions.campaign_id')
                ->where('view_submissions.status', SubmissionStatus::Approved->value)
                ->sum('view_submissions.views_count'),
            'wallet_balance' => $user->wallet?->balance ?? 0,
        ];

        $recentCampaigns = $user->campaigns()->latest()->take(8)->get();

        return view('advertiser.dashboard', compact('stats', 'recentCampaigns'));
    }
}
