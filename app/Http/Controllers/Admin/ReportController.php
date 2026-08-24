<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubmissionStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\WalletTransaction;
use App\Models\ViewSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * گزارش‌های مالی و آماری
     */
    public function index(Request $request): View
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $transactionsQuery = WalletTransaction::query();
        $viewsQuery = ViewSubmission::where('status', SubmissionStatus::Approved->value);

        if ($from) {
            $transactionsQuery->whereDate('created_at', '>=', $from);
            $viewsQuery->whereDate('submitted_at', '>=', $from);
        }
        if ($to) {
            $transactionsQuery->whereDate('created_at', '<=', $to);
            $viewsQuery->whereDate('submitted_at', '<=', $to);
        }

        $financial = [
            'total_credits' => (clone $transactionsQuery)->where('type', TransactionType::Credit->value)->sum('amount'),
            'total_debits' => (clone $transactionsQuery)->where('type', TransactionType::Debit->value)->sum('amount'),
            'total_commission' => (clone $transactionsQuery)
                ->where('reference_type', 'commission')->sum('amount'),
        ];

        $activity = [
            'total_approved_views' => (int) (clone $viewsQuery)->sum('views_count'),
            'total_submissions' => (clone $viewsQuery)->count(),
            'total_campaigns' => Campaign::count(),
        ];

        $recentTransactions = (clone $transactionsQuery)
            ->with('user')
            ->latest()
            ->take(25)
            ->get();

        return view('admin.reports.index', compact('financial', 'activity', 'recentTransactions', 'from', 'to'));
    }
}
