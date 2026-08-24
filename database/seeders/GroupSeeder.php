<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * سطوح کاربری (۱، ۲ و ۳) با محدودیت دریافت تبلیغ
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'سطح ۱',
                'code' => 'level-1',
                'daily_campaign_limit' => 1,
                'weekly_campaign_limit' => 3,
                'min_avg_views' => 0,
                'description' => 'سفیران تازه‌کار با کمترین سقف دریافت تبلیغ',
            ],
            [
                'name' => 'سطح ۲',
                'code' => 'level-2',
                'daily_campaign_limit' => 2,
                'weekly_campaign_limit' => 7,
                'min_avg_views' => 500,
                'description' => 'سفیران فعال با میانگین ویوی متوسط',
            ],
            [
                'name' => 'سطح ۳',
                'code' => 'level-3',
                'daily_campaign_limit' => 4,
                'weekly_campaign_limit' => 14,
                'min_avg_views' => 2000,
                'description' => 'سفیران برتر با بالاترین سقف دریافت تبلیغ',
            ],
        ];

        foreach ($groups as $group) {
            Group::updateOrCreate(['code' => $group['code']], $group);
        }
    }
}
