<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * فرم تنظیمات سامانه
     */
    public function index(): View
    {
        $settings = Setting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * ذخیره تنظیمات
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'min_withdrawal_amount' => ['nullable', 'integer', 'min:0'],
            'site_name' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $type = is_numeric($value) ? 'integer' : 'string';
                if ($key === 'commission_rate') {
                    $type = 'string';
                }
                Setting::set($key, $value, $type);
            }
        }

        ActivityLog::record(auth()->id(), 'settings.updated', 'به‌روزرسانی تنظیمات سامانه');

        return back()->with('success', 'تنظیمات ذخیره شد.');
    }
}
