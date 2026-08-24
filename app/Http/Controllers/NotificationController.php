<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * فهرست اعلان‌های کاربر
     */
    public function index(): View
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * علامت‌گذاری یک اعلان به‌عنوان خوانده‌شده
     */
    public function markRead(int $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back();
    }

    /**
     * علامت‌گذاری همه اعلان‌ها به‌عنوان خوانده‌شده
     */
    public function markAllRead(Request $request): RedirectResponse
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('success', 'همه اعلان‌ها خوانده شدند.');
    }
}
