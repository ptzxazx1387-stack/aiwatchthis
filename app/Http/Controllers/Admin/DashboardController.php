<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CampaignStatus;
use App\Enums\SubmissionStatus;
use App\Enums\WithdrawalStatus;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\User;
use App\Models\ViewSubmission;
use App\Models\WithdrawalRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * داشبورد مدیر سیستم
     */
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_advertisers' => User::where('role', 'advertiser')->count(),
            'total_ambassadors' => User::where('role', 'ambassador')->count(),
            'active_campaigns' => Campaign::where('status', CampaignStatus::Active->value)->count(),
            'pending_campaigns' => Campaign::where('status', CampaignStatus::Pending->value)->count(),
            'pending_submissions' => ViewSubmission::pending()->count(),
            'pending_withdrawals' => WithdrawalRequest::pending()->count(),
            'total_approved_views' => (int) ViewSubmission::approved()->sum('views_count'),
        ];

        $recentSubmissions = ViewSubmission::with(['ambassador', 'campaign'])
            ->latest()
            ->take(8)
            ->get();

        $recentCampaigns = Campaign::with('advertiser')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSubmissions', 'recentCampaigns'));
    }
}
