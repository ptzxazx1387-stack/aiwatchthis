<?php

namespace App\Http\Controllers\Ambassador;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Group;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * نمایش و ویرایش پروفایل سفیر
     */
    public function edit(): View
    {
        $profile = auth()->user()->ambassadorProfile;
        $categories = Category::where('is_active', true)->get();
        $provinces = Province::where('is_active', true)->get();
        $groups = Group::where('is_active', true)->get();

        return view('ambassador.profile.edit', compact('profile', 'categories', 'provinces', 'groups'));
    }

    /**
     * ذخیره پروفایل سفیر
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ig_username' => ['required', 'string', 'max:50'],
            'ig_profile_url' => ['nullable', 'url', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'province_id' => ['required', 'exists:provinces,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'followers_count' => ['nullable', 'integer', 'min:0'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $profile = auth()->user()->ambassadorProfile;

        if ($profile) {
            $profile->update($data);
        } else {
            $data['user_id'] = auth()->id();
            $data['status'] = 'pending'; // نیاز به تأیید مدیر
            auth()->user()->ambassadorProfile()->create($data);
        }

        ActivityLog::record(auth()->id(), 'profile.updated', 'به‌روزرسانی پروفایل سفیر');

        return back()->with('success', 'پروفایل شما ذخیره شد.');
    }
}
