<?php

use App\Http\Controllers\Ambassador\AssignmentController;
use App\Http\Controllers\Ambassador\CampaignController as AmbassadorCampaignController;
use App\Http\Controllers\Ambassador\DashboardController as AmbassadorDashboardController;
use App\Http\Controllers\Ambassador\ProfileController as AmbassadorProfileController;
use App\Http\Controllers\Ambassador\SubmissionController as AmbassadorSubmissionController;
use App\Http\Controllers\Advertiser\CampaignController as AdvertiserCampaignController;
use App\Http\Controllers\Advertiser\DashboardController as AdvertiserDashboardController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/* ------------------------------------------------------------------ */
/*  صفحه نخست / ورود                                                   */
/* ------------------------------------------------------------------ */
Route::get('/', fn () => redirect()->route('dashboard'));

/* ------------------------------------------------------------------ */
/*  احراز هویت                                                         */
/* ------------------------------------------------------------------ */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/* ------------------------------------------------------------------ */
/*  مسیرهای مشترک (کاربر واردشده)                                     */
/* ------------------------------------------------------------------ */
Route::middleware(['auth', 'active'])->group(function () {
    // هدایت به داشبورد متناسب با نقش
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->dashboardRoute());
    })->name('dashboard');

    // کیف پول
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/withdraw', [WalletController::class, 'requestWithdrawal'])->name('wallet.withdraw');

    // اعلان‌ها
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // انتخاب شهر وابسته به استان (AJAX)
    Route::get('/api/cities', [GeoController::class, 'cities'])->name('geo.cities');
});

/* ------------------------------------------------------------------ */
/*  مدیریت (admin)                                                     */
/* ------------------------------------------------------------------ */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'active', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // کمپین‌ها
    Route::get('/campaigns', [AdminCampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{campaign}', [AdminCampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaigns/{campaign}/approve', [AdminCampaignController::class, 'approve'])->name('campaigns.approve');
    Route::post('/campaigns/{campaign}/reject', [AdminCampaignController::class, 'reject'])->name('campaigns.reject');
    Route::post('/campaigns/{campaign}/pause', [AdminCampaignController::class, 'pause'])->name('campaigns.pause');
    Route::post('/campaigns/{campaign}/auto-assign', [AdminCampaignController::class, 'autoAssign'])->name('campaigns.autoAssign');

    // ثبت‌ویوها
    Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::post('/submissions/{submission}/approve', [AdminSubmissionController::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{submission}/reject', [AdminSubmissionController::class, 'reject'])->name('submissions.reject');

    // کاربران و سفیران
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/ambassadors', [UserController::class, 'ambassadors'])->name('ambassadors.index');
    Route::post('/ambassadors/{profile}/verify', [UserController::class, 'verifyProfile'])->name('ambassadors.verify');
    Route::post('/ambassadors/{profile}/toggle-status', [UserController::class, 'toggleProfileStatus'])->name('ambassadors.toggleStatus');
    Route::post('/ambassadors/{profile}/change-group', [UserController::class, 'changeGroup'])->name('ambassadors.changeGroup');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::post('/users/{user}/change-role', [UserController::class, 'changeRole'])->name('users.changeRole');

    // برداشت‌ها
    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [WithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // گروه‌ها (سطوح)
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::post('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

    // گزارش‌ها و تنظیمات
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

/* ------------------------------------------------------------------ */
/*  تبلیغ‌دهنده (advertiser)                                           */
/* ------------------------------------------------------------------ */
Route::prefix('advertiser')->name('advertiser.')->middleware(['auth', 'active', 'role:advertiser,admin'])->group(function () {
    Route::get('/dashboard', [AdvertiserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/campaigns', [AdvertiserCampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [AdvertiserCampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [AdvertiserCampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [AdvertiserCampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [AdvertiserCampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [AdvertiserCampaignController::class, 'update'])->name('campaigns.update');
    Route::post('/campaigns/{campaign}/auto-assign', [AdvertiserCampaignController::class, 'autoAssign'])->name('campaigns.autoAssign');
});

/* ------------------------------------------------------------------ */
/*  سفیر (ambassador)                                                  */
/* ------------------------------------------------------------------ */
Route::prefix('ambassador')->name('ambassador.')->middleware(['auth', 'active', 'role:ambassador,admin'])->group(function () {
    Route::get('/dashboard', [AmbassadorDashboardController::class, 'index'])->name('dashboard');

    // بازار کمپین‌ها
    Route::get('/campaigns', [AmbassadorCampaignController::class, 'index'])->name('campaigns.index');

    // تخصیص‌ها
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::post('/assignments/{assignment}/accept', [AssignmentController::class, 'accept'])->name('assignments.accept');
    Route::post('/assignments/{assignment}/decline', [AssignmentController::class, 'decline'])->name('assignments.decline');

    // ثبت ویو
    Route::get('/submissions', [AmbassadorSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/create/{assignment}', [AmbassadorSubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions/{assignment}', [AmbassadorSubmissionController::class, 'store'])->name('submissions.store');

    // پروفایل
    Route::get('/profile', [AmbassadorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AmbassadorProfileController::class, 'update'])->name('profile.update');
});
