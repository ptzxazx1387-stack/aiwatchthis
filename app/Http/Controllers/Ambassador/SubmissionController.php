<?php

namespace App\Http\Controllers\Ambassador;

use App\Enums\AssignmentStatus;
use App\Enums\SubmissionStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CampaignAssignment;
use App\Models\ViewSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    /**
     * فهرست ثبت‌ویوهای سفیر
     */
    public function index(): View
    {
        $submissions = auth()->user()
            ->viewSubmissions()
            ->with('campaign')
            ->latest()
            ->paginate(12);

        return view('ambassador.submissions.index', compact('submissions'));
    }

    /**
     * فرم ثبت ویو برای یک تخصیص
     */
    public function create(CampaignAssignment $assignment): View
    {
        $this->authorize('manage', $assignment);

        $assignment->load('campaign');

        return view('ambassador.submissions.create', compact('assignment'));
    }

    /**
     * ثبت تعداد ویو و آپلود اسکرین‌شات
     */
    public function store(Request $request, CampaignAssignment $assignment): RedirectResponse
    {
        $this->authorize('manage', $assignment);

        $data = $request->validate([
            'views_count' => ['required', 'integer', 'min:1'],
            'screenshot' => ['required', 'image', 'max:5120'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $screenshotPath = $request->file('screenshot')->store('screenshots', 'uploads');

        $submission = ViewSubmission::create([
            'assignment_id' => $assignment->id,
            'campaign_id' => $assignment->campaign_id,
            'ambassador_id' => auth()->id(),
            'views_count' => $data['views_count'],
            'screenshot_path' => $screenshotPath,
            'description' => $data['description'] ?? null,
            'status' => SubmissionStatus::Pending->value,
            'submitted_at' => now(),
        ]);

        ActivityLog::record(
            auth()->id(),
            'submission.created',
            "ثبت ویوی کمپین «{$assignment->campaign->title}»",
            $submission,
        );

        return redirect()
            ->route('ambassador.submissions.index')
            ->with('success', 'ثبت‌ویو با موفقیت ارسال شد و در انتظار تأیید مدیر است.');
    }
}
