<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CampaignStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Campaign;
use App\Models\Notification;
use App\Services\AssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * فهرست همه کمپین‌ها (مدیریت)
     */
    public function index(Request $request): View
    {
        $campaigns = Campaign::with(['advertiser', 'category'])
            ->filter($request->only(['province_id', 'city_id', 'category_id', 'status']))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * جزئیات یک کمپین
     */
    public function show(Campaign $campaign): View
    {
        $campaign->load(['advertiser', 'category', 'province', 'city', 'assignments.ambassador', 'viewSubmissions.ambassador']);

        return view('admin.campaigns.show', compact('campaign'));
    }

    /**
     * تأیید کمپین و فعال‌سازی آن
     */
    public function approve(Campaign $campaign): RedirectResponse
    {
        $campaign->update([
            'status' => CampaignStatus::Active->value,
            'start_date' => $campaign->start_date ?? now(),
        ]);

        ActivityLog::record(auth()->id(), 'campaign.approved', "تأیید کمپین «{$campaign->title}»", $campaign);

        Notification::send(
            $campaign->advertiser_id,
            'کمپین شما تأیید شد',
            "کمپین «{$campaign->title}» تأیید و فعال شد.",
            'success',
            route('advertiser.campaigns.show', $campaign),
        );

        return back()->with('success', 'کمپین تأیید و فعال شد.');
    }

    /**
     * رد کمپین
     */
    public function reject(Request $request, Campaign $campaign): RedirectResponse
    {
        $request->validate(['admin_note' => ['nullable', 'string', 'max:1000']]);

        $campaign->update([
            'status' => CampaignStatus::Cancelled->value,
            'admin_note' => $request->input('admin_note'),
        ]);

        ActivityLog::record(auth()->id(), 'campaign.rejected', "رد کمپین «{$campaign->title}»", $campaign);

        Notification::send(
            $campaign->advertiser_id,
            'کمپین شما رد شد',
            "کمپین «{$campaign->title}» رد شد. " . ($request->input('admin_note') ?: ''),
            'danger',
            route('advertiser.campaigns.show', $campaign),
        );

        return back()->with('success', 'کمپین رد شد.');
    }

    /**
     * توقف موقت کمپین
     */
    public function pause(Campaign $campaign): RedirectResponse
    {
        $campaign->update(['status' => CampaignStatus::Paused->value]);

        ActivityLog::record(auth()->id(), 'campaign.paused', "توقف کمپین «{$campaign->title}»", $campaign);

        return back()->with('success', 'کمپین متوقف شد.');
    }

    /**
     * اجرای تخصیص خودکار کمپین
     */
    public function autoAssign(Campaign $campaign, AssignmentService $service): RedirectResponse
    {
        $result = $service->autoAssign($campaign);

        ActivityLog::record(
            auth()->id(),
            'campaign.auto_assign',
            "تخصیص خودکار کمپین «{$campaign->title}» - {$result['assigned']} سفیر تخصیص یافت",
            $campaign,
        );

        $msg = sprintf('%d سفیر به کمپین تخصیص یافت.', $result['assigned']);

        return back()->with('success', $msg);
    }
}
