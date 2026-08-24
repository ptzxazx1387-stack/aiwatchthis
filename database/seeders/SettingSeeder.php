<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * تنظیمات پیش‌فرض سامانه
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'سامانه مدیریت کمپین استوری اینستاگرام', 'type' => 'string', 'group' => 'general', 'label' => 'نام سامانه'],
            ['key' => 'commission_rate', 'value' => '10', 'type' => 'string', 'group' => 'financial', 'label' => 'نرخ کمیسیون سامانه (درصد)'],
            ['key' => 'min_withdrawal_amount', 'value' => '100000', 'type' => 'integer', 'group' => 'financial', 'label' => 'حداقل مبلغ برداشت (تومان)'],
            ['key' => 'default_price_per_view', 'value' => '1000', 'type' => 'integer', 'group' => 'financial', 'label' => 'قیمت پیش‌فرض هر ویو (تومان)'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
