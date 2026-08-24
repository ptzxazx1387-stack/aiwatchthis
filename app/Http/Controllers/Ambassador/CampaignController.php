<?php

namespace App\Http\Controllers\Ambassador;

use App\Enums\CampaignStatus;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * مرور کمپین‌های فعال (بازار کمپین) با فیلتر استان، شهر و دسته‌بندی
     */
    public function index(Request $request): View
    {
        $campaigns = Campaign::active()
            ->with(['advertiser', 'category', 'province', 'city'])
            ->filter($request->only(['province_id', 'city_id', 'category_id']))
            ->where('remaining_capacity', '>', 0)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('is_active', true)->get();
        $provinces = Province::where('is_active', true)->get();

        return view('ambassador.campaigns.index', compact('campaigns', 'categories', 'provinces'));
    }
}
