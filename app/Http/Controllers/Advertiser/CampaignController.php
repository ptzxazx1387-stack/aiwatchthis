<?php

namespace App\Http\Controllers\Advertiser;

use App\Enums\CampaignStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Province;
use App\Models\Setting;
use App\Services\AssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * فهرست کمپین‌های تبلیغ‌دهنده
     */
    public function index(Request $request): View
    {
        $campaigns = auth()->user()
            ->campaigns()
            ->with('category')
            ->filter($request->only(['province_id', 'city_id', 'category_id', 'status']))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('advertiser.campaigns.index', compact('campaigns'));
    }

    /**
     * فرم ایجاد کمپین جدید
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();
        $provinces = Province::where('is_active', true)->get();

        return view('advertiser.campaigns.create', compact('categories', 'provinces'));
    }

    /**
     * ذخیره کمپین جدید
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'story_content' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'price_per_view' => ['required', 'integer', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
            'min_avg_views' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $data['advertiser_id'] = auth()->id();
        $data['remaining_capacity'] = $data['capacity'];
        $data['status'] = CampaignStatus::Pending->value; // نیاز به تأیید مدیر

        $campaign = Campaign::create($data);

        ActivityLog::record(auth()->id(), 'campaign.created', "ایجاد کمپین «{$campaign->title}»", $campaign);

        return redirect()
            ->route('advertiser.campaigns.show', $campaign)
            ->with('success', 'کمپین ثبت شد و در انتظار تأیید مدیر است.');
    }

    /**
     * نمایش کمپین
     */
    public function show(Campaign $campaign): View
    {
        $this->authorize('view', $campaign);

        $campaign->load(['category', 'province', 'city', 'assignments.ambassador', 'viewSubmissions.ambassador']);

        return view('advertiser.campaigns.show', compact('campaign'));
    }

    /**
     * فرم ویرایش کمپین
     */
    public function edit(Campaign $campaign): View
    {
        $this->authorize('update', $campaign);

        $categories = Category::where('is_active', true)->get();
        $provinces = Province::where('is_active', true)->get();

        return view('advertiser.campaigns.edit', compact('campaign', 'categories', 'provinces'));
    }

    /**
     * به‌روزرسانی کمپین
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $this->authorize('update', $campaign);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'story_content' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'price_per_view' => ['required', 'integer', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
            'min_avg_views' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // به‌روزرسانی ظرفیت باقیمانده متناسب با ظرفیت جدید
        $capacityDiff = $data['capacity'] - $campaign->capacity;
        $data['remaining_capacity'] = max(0, $campaign->remaining_capacity + $capacityDiff);

        $campaign->update($data);

        ActivityLog::record(auth()->id(), 'campaign.updated', "ویرایش کمپین «{$campaign->title}»", $campaign);

        return redirect()
            ->route('advertiser.campaigns.show', $campaign)
            ->with('success', 'کمپین به‌روزرسانی شد.');
    }

    /**
     * اجرای تخصیص خودکار (توسط تبلیغ‌دهنده بعد از فعال شدن کمپین)
     */
    public function autoAssign(Campaign $campaign, AssignmentService $service): RedirectResponse
    {
        $this->authorize('update', $campaign);

        $result = $service->autoAssign($campaign);

        return back()->with('success', sprintf('%d سفیر به کمپین شما تخصیص یافت.', $result['assigned']));
    }
}
