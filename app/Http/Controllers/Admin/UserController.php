<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProfileStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AmbassadorProfile;
use App\Models\Group;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * فهرست کاربران
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * فهرست سفیران و پروفایل‌های آنان
     */
    public function ambassadors(Request $request): View
    {
        $query = AmbassadorProfile::with(['user', 'category', 'province', 'city', 'group']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $profiles = $query->latest()->paginate(20)->withQueryString();
        $groups = Group::all();

        return view('admin.users.ambassadors', compact('profiles', 'groups'));
    }

    /**
     * تأیید پروفایل سفیر
     */
    public function verifyProfile(AmbassadorProfile $profile): RedirectResponse
    {
        $profile->update([
            'status' => ProfileStatus::Active->value,
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        ActivityLog::record(auth()->id(), 'ambassador.verified', "تأیید پروفایل سفیر {$profile->user->name}", $profile);

        Notification::send(
            $profile->user_id,
            'پروفایل شما تأیید شد',
            'پروفایل اینستاگرام شما تأیید شد و اکنون می‌توانید تبلیغ دریافت کنید.',
            'success',
        );

        return back()->with('success', 'پروفایل سفیر تأیید شد.');
    }

    /**
     * تعلیق/فعال‌سازی پروفایل سفیر
     */
    public function toggleProfileStatus(AmbassadorProfile $profile): RedirectResponse
    {
        $newStatus = $profile->status === ProfileStatus::Active
            ? ProfileStatus::Suspended
            : ProfileStatus::Active;

        $profile->update(['status' => $newStatus->value]);

        ActivityLog::record(auth()->id(), 'ambassador.status_changed', "تغییر وضعیت سفیر {$profile->user->name} به {$newStatus->label()}", $profile);

        return back()->with('success', 'وضعیت پروفایل به‌روزرسانی شد.');
    }

    /**
     * تغییر سطح (گروه) سفیر
     */
    public function changeGroup(Request $request, AmbassadorProfile $profile): RedirectResponse
    {
        $request->validate(['group_id' => ['required', 'exists:groups,id']]);

        $profile->update(['group_id' => $request->input('group_id')]);

        ActivityLog::record(auth()->id(), 'ambassador.group_changed', "تغییر سطح سفیر {$profile->user->name}", $profile);

        return back()->with('success', 'سطح سفیر تغییر کرد.');
    }

    /**
     * تغییر وضعیت حساب کاربری (فعال/معلق)
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update(['status' => $user->status === 'active' ? 'suspended' : 'active']);

        ActivityLog::record(auth()->id(), 'user.status_changed', "تغییر وضعیت حساب {$user->name}", $user);

        return back()->with('success', 'وضعیت حساب کاربری تغییر کرد.');
    }

    /**
     * تغییر نقش کاربر
     */
    public function changeRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => ['required', 'in:admin,advertiser,ambassador']]);

        $user->update(['role' => UserRole::from($request->input('role'))->value]);

        ActivityLog::record(auth()->id(), 'user.role_changed', "تغییر نقش {$user->name}", $user);

        return back()->with('success', 'نقش کاربر تغییر کرد.');
    }
}
