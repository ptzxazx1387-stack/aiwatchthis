<?php

namespace App\Http\Controllers\Ambassador;

use App\Enums\AssignmentStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CampaignAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    /**
     * فهرست کمپین‌های تخصیص‌یافته به سفیر
     */
    public function index(Request $request): View
    {
        $query = auth()->user()->assignments()->with('campaign');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $assignments = $query->latest()->paginate(12)->withQueryString();

        return view('ambassador.assignments.index', compact('assignments'));
    }

    /**
     * پذیرش یک تخصیص
     */
    public function accept(CampaignAssignment $assignment): RedirectResponse
    {
        $this->authorize('manage', $assignment);

        $assignment->update([
            'status' => AssignmentStatus::Accepted->value,
            'accepted_at' => now(),
        ]);

        ActivityLog::record(auth()->id(), 'assignment.accepted', "پذیرش کمپین «{$assignment->campaign->title}»", $assignment);

        return back()->with('success', 'کمپین پذیرفته شد. لطفاً استوری را منتشر و نتیجه را ثبت کنید.');
    }

    /**
     * رد یک تخصیص
     */
    public function decline(Request $request, CampaignAssignment $assignment): RedirectResponse
    {
        $this->authorize('manage', $assignment);

        $assignment->update([
            'status' => AssignmentStatus::Declined->value,
            'decline_reason' => $request->input('reason'),
        ]);

        // بازگرداندن ظرفیت کمپین
        $assignment->campaign->increment('remaining_capacity');

        ActivityLog::record(auth()->id(), 'assignment.declined', "رد کمپین «{$assignment->campaign->title}»", $assignment);

        return back()->with('success', 'کمپین رد شد.');
    }
}
