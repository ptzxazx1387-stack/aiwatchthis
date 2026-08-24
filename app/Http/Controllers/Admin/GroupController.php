<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    /**
     * فهرست گروه‌ها (سطوح کاربری)
     */
    public function index(): View
    {
        $groups = Group::withCount('ambassadorProfiles')->get();

        return view('admin.groups.index', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', 'unique:groups,code'],
            'daily_campaign_limit' => ['required', 'integer', 'min:1'],
            'weekly_campaign_limit' => ['required', 'integer', 'min:1'],
            'min_avg_views' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        Group::create($data);

        ActivityLog::record(auth()->id(), 'group.created', "ایجاد گروه «{$data['name']}»");

        return back()->with('success', 'گروه جدید ایجاد شد.');
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'daily_campaign_limit' => ['required', 'integer', 'min:1'],
            'weekly_campaign_limit' => ['required', 'integer', 'min:1'],
            'min_avg_views' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $group->update($data);

        ActivityLog::record(auth()->id(), 'group.updated', "ویرایش گروه «{$group->name}»", $group);

        return back()->with('success', 'گروه به‌روزرسانی شد.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        $group->delete();

        ActivityLog::record(auth()->id(), 'group.deleted', "حذف گروه «{$group->name}»");

        return back()->with('success', 'گروه حذف شد.');
    }
}
