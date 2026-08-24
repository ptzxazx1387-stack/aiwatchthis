<?php

namespace App\Http\Controllers\Ambassador;

use App\Enums\AssignmentStatus;
use App\Enums\SubmissionStatus;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * داشبورد سفیر
     */
    public function index(): View
    {
        $user = auth()->user();

        $stats = [
            'assigned_campaigns' => $user->assignments()->where('status', AssignmentStatus::Assigned->value)->count(),
            'accepted_campaigns' => $user->assignments()->where('status', AssignmentStatus::Accepted->value)->count(),
            'completed_campaigns' => $user->assignments()->where('status', AssignmentStatus::Completed->value)->count(),
            'pending_submissions' => $user->viewSubmissions()->pending()->count(),
            'approved_submissions' => $user->viewSubmissions()->approved()->count(),
            'total_views_7d' => (int) $user->viewSubmissions()
                ->approved()
                ->where('submitted_at', '>=', now()->subDays(7))
                ->sum('views_count'),
            'wallet_balance' => $user->wallet?->balance ?? 0,
        ];

        $myAssignments = $user->assignments()
            ->with('campaign')
            ->whereIn('status', [AssignmentStatus::Assigned->value, AssignmentStatus::Accepted->value])
            ->latest()
            ->take(8)
            ->get();

        return view('ambassador.dashboard', compact('stats', 'myAssignments'));
    }
}
