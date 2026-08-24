<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AssignmentStatus;
use App\Enums\SubmissionStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\ViewSubmission;
use App\Services\AmbassadorStatsService;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    /**
     * فهرست ثبت‌ویوها (پیش‌فرض: در انتظار تأیید)
     */
    public function index(Request $request): View
    {
        $query = ViewSubmission::with(['ambassador', 'campaign', 'assignment']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        } else {
            $query->pending();
        }

        $submissions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.submissions.index', compact('submissions'));
    }

    /**
     * تأیید ثبت‌ویو و پرداخت درآمد سفیر
     */
    public function approve(Request $request, ViewSubmission $submission, WalletService $wallet, AmbassadorStatsService $stats): RedirectResponse
    {
        if ($submission->status !== SubmissionStatus::Pending) {
            return back()->with('error', 'این ثبت‌ویو قبلاً بررسی شده است.');
        }

        $submission->update([
            'status' => SubmissionStatus::Approved->value,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_note' => $request->input('review_note'),
        ]);

        // محاسبه و واریز درآمد سفیر
        $campaign = $submission->campaign;
        $earning = $wallet->calculateEarning(
            $submission->views_count,
            $campaign->price_per_view,
            $campaign->commission_rate,
        );

        $wallet->credit(
            $submission->ambassador,
            $earning,
            "درآمد تأییدشده کمپین «{$campaign->title}»",
            'campaign_earning',
            $submission->id,
        );

        // به‌روزرسانی میانگین ویوی ۷ روزه سفیر
        $stats->refreshAvgViews($submission->ambassador);

        // تکمیل تخصیص
        $submission->assignment->update([
            'status' => AssignmentStatus::Completed->value,
            'completed_at' => now(),
        ]);

        ActivityLog::record(auth()->id(), 'submission.approved', "تأیید ثبت‌ویوی #{$submission->id}", $submission);

        Notification::send(
            $submission->ambassador_id,
            'ثبت‌ویو تأیید شد',
            "ثبت‌ویوی شما تأیید و مبلغ " . number_format($earning) . " تومان به کیف پول شما واریز شد.",
            'success',
            route('ambassador.submissions.index'),
        );

        return back()->with('success', 'ثبت‌ویو تأیید و درآمد سفیر واریز شد.');
    }

    /**
     * رد ثبت‌ویو
     */
    public function reject(Request $request, ViewSubmission $submission): RedirectResponse
    {
        $request->validate(['review_note' => ['required', 'string', 'max:1000']]);

        $submission->update([
            'status' => SubmissionStatus::Rejected->value,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_note' => $request->input('review_note'),
        ]);

        ActivityLog::record(auth()->id(), 'submission.rejected', "رد ثبت‌ویوی #{$submission->id}", $submission);

        Notification::send(
            $submission->ambassador_id,
            'ثبت‌ویو رد شد',
            'ثبت‌ویوی شما رد شد. دلیل: ' . $request->input('review_note'),
            'danger',
            route('ambassador.submissions.index'),
        );

        return back()->with('success', 'ثبت‌ویو رد شد.');
    }
}
