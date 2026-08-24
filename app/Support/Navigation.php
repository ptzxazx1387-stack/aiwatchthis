<?php

namespace App\Support;

use App\Models\User;

/**
 * منبع یگانه‌ی مسیرهای پیمایش.
 *
 * هم سایدبار، هم داک پایین موبایل و هم «جعبه‌ی فرمان» (Ctrl+K) از همین
 * یک تعریف ساخته می‌شوند تا هرگز از هم جدا نیفتند.
 *
 * هر آیتم:
 *   label    عنوان فارسی
 *   route    نام مسیر
 *   icon     نام آیکون در x-icon
 *   match    الگوی routeIs برای حالت «صفحه‌ی جاری»
 *   keywords واژه‌های کمکی برای جست‌وجو
 *   count    عدد اختیاری کنار آیتم
 *   dock     اگر true باشد در داک موبایل هم می‌آید
 */
final class Navigation
{
    /**
     * @return array<int, array{label: string, items: array<int, array<string, mixed>>}>
     */
    public static function groups(User $user): array
    {
        $unread = $user->unreadNotifications()->count();

        $shared = [
            'label' => 'حساب',
            'items' => [
                ['label' => 'کیف پول', 'route' => 'wallet.index', 'icon' => 'wallet', 'match' => 'wallet.*', 'keywords' => 'موجودی برداشت تراکنش پول', 'dock' => true],
                ['label' => 'اعلان‌ها', 'route' => 'notifications.index', 'icon' => 'bell', 'match' => 'notifications.*', 'keywords' => 'پیام خبر', 'count' => $unread],
            ],
        ];

        if ($user->isAdmin()) {
            return [
                [
                    'label' => 'مرور',
                    'items' => [
                        ['label' => 'داشبورد', 'route' => 'admin.dashboard', 'icon' => 'home', 'match' => 'admin.dashboard', 'keywords' => 'خانه آمار خلاصه', 'dock' => true],
                        ['label' => 'گزارش‌ها', 'route' => 'admin.reports.index', 'icon' => 'chart', 'match' => 'admin.reports.*', 'keywords' => 'مالی آمار درآمد کمیسیون'],
                    ],
                ],
                [
                    'label' => 'کارتابل',
                    'items' => [
                        ['label' => 'تأیید ویوها', 'route' => 'admin.submissions.index', 'icon' => 'check', 'match' => 'admin.submissions.*', 'keywords' => 'ثبت ویو بررسی تایید رد', 'dock' => true],
                        ['label' => 'کمپین‌ها', 'route' => 'admin.campaigns.index', 'icon' => 'megaphone', 'match' => 'admin.campaigns.*', 'keywords' => 'تبلیغ آگهی', 'dock' => true],
                        ['label' => 'برداشت‌ها', 'route' => 'admin.withdrawals.index', 'icon' => 'banknote', 'match' => 'admin.withdrawals.*', 'keywords' => 'پول شبا تسویه'],
                    ],
                ],
                [
                    'label' => 'مردم',
                    'items' => [
                        ['label' => 'سفیران', 'route' => 'admin.ambassadors.index', 'icon' => 'star', 'match' => 'admin.ambassadors.*', 'keywords' => 'پیج اینستاگرام تایید پروفایل'],
                        ['label' => 'کاربران', 'route' => 'admin.users.index', 'icon' => 'users', 'match' => 'admin.users.*', 'keywords' => 'حساب نقش تعلیق'],
                        ['label' => 'سطوح کاربری', 'route' => 'admin.groups.index', 'icon' => 'layers', 'match' => 'admin.groups.*', 'keywords' => 'گروه سقف محدودیت'],
                    ],
                ],
                [
                    'label' => 'سامانه',
                    'items' => [
                        ['label' => 'تنظیمات', 'route' => 'admin.settings.index', 'icon' => 'settings', 'match' => 'admin.settings.*', 'keywords' => 'کمیسیون حداقل برداشت نام'],
                    ],
                ],
                $shared,
            ];
        }

        if ($user->isAdvertiser()) {
            return [
                [
                    'label' => 'کمپین',
                    'items' => [
                        ['label' => 'داشبورد', 'route' => 'advertiser.dashboard', 'icon' => 'home', 'match' => 'advertiser.dashboard', 'keywords' => 'خانه آمار', 'dock' => true],
                        ['label' => 'کمپین‌های من', 'route' => 'advertiser.campaigns.index', 'icon' => 'megaphone', 'match' => 'advertiser.campaigns.index|advertiser.campaigns.show|advertiser.campaigns.edit', 'keywords' => 'تبلیغ لیست', 'dock' => true],
                        ['label' => 'کمپین جدید', 'route' => 'advertiser.campaigns.create', 'icon' => 'plus', 'match' => 'advertiser.campaigns.create', 'keywords' => 'ایجاد ساخت افزودن تبلیغ', 'dock' => true],
                    ],
                ],
                $shared,
            ];
        }

        return [
            [
                'label' => 'سفیر',
                'items' => [
                    ['label' => 'داشبورد', 'route' => 'ambassador.dashboard', 'icon' => 'home', 'match' => 'ambassador.dashboard', 'keywords' => 'خانه آمار', 'dock' => true],
                    ['label' => 'بازار کمپین‌ها', 'route' => 'ambassador.campaigns.index', 'icon' => 'search', 'match' => 'ambassador.campaigns.*', 'keywords' => 'جست‌وجو پیدا کردن تبلیغ جدید', 'dock' => true],
                    ['label' => 'تبلیغات من', 'route' => 'ambassador.assignments.index', 'icon' => 'inbox', 'match' => 'ambassador.assignments.*', 'keywords' => 'تخصیص پذیرش رد', 'dock' => true],
                    ['label' => 'ثبت‌ویوهای من', 'route' => 'ambassador.submissions.index', 'icon' => 'upload', 'match' => 'ambassador.submissions.*', 'keywords' => 'ارسال اسکرین‌شات ویو'],
                    ['label' => 'پروفایل', 'route' => 'ambassador.profile.edit', 'icon' => 'user', 'match' => 'ambassador.profile.*', 'keywords' => 'اینستاگرام یوزرنیم دنبال‌کننده'],
                ],
            ],
            $shared,
        ];
    }

    /**
     * فهرست مسطح برای جعبه‌ی فرمان.
     *
     * @return array<int, array{label: string, url: string, group: string, keywords: string, hint: ?string}>
     */
    public static function paletteItems(User $user): array
    {
        $out = [];

        foreach (self::groups($user) as $group) {
            foreach ($group['items'] as $item) {
                $out[] = [
                    'label' => $item['label'],
                    'url' => route($item['route']),
                    'group' => $group['label'],
                    'keywords' => $item['keywords'] ?? '',
                    'hint' => ! empty($item['count']) ? Fmt::num($item['count']).' تازه' : null,
                ];
            }
        }

        return $out;
    }
}
