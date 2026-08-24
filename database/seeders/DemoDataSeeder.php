<?php

namespace Database\Seeders;

use App\Enums\AssignmentStatus;
use App\Enums\CampaignStatus;
use App\Enums\ProfileStatus;
use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Models\AmbassadorProfile;
use App\Models\Campaign;
use App\Models\CampaignAssignment;
use App\Models\Category;
use App\Models\City;
use App\Models\Group;
use App\Models\Province;
use App\Models\User;
use App\Models\ViewSubmission;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * داده نمونه برای نمایش و تست سامانه
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        // تبلیغ‌دهنده نمونه
        $advertiser = User::firstOrCreate(
            ['email' => 'advertiser@example.com'],
            [
                'name' => 'شرکت تبلیغاتی نمونه',
                'email' => 'advertiser@example.com',
                'phone' => '09121111111',
                'password' => Hash::make('password'),
                'role' => UserRole::Advertiser->value,
                'status' => 'active',
            ],
        );
        Wallet::firstOrCreate(['user_id' => $advertiser->id]);

        $category = Category::where('slug', 'beauty')->first();
        $province = Province::where('name', 'تهران')->first();
        $city = City::where('name', 'تهران')->first();
        $group1 = Group::where('code', 'level-1')->first();
        $group2 = Group::where('code', 'level-2')->first();

        // سفیران نمونه
        $ambassadors = [
            ['name' => 'سفیر یک', 'email' => 'ambassador1@example.com', 'ig' => 'amb1', 'followers' => 25000, 'avg' => 3200, 'group' => $group2, 'category' => $category, 'province' => $province, 'city' => $city],
            ['name' => 'سفیر دو', 'email' => 'ambassador2@example.com', 'ig' => 'amb2', 'followers' => 8000, 'avg' => 900, 'group' => $group1, 'category' => $category, 'province' => $province, 'city' => $city],
            ['name' => 'سفیر سه', 'email' => 'ambassador3@example.com', 'ig' => 'amb3', 'followers' => 50000, 'avg' => 6100, 'group' => $group2, 'category' => $category, 'province' => $province, 'city' => $city],
        ];

        $ambassadorUsers = [];
        foreach ($ambassadors as $a) {
            $user = User::firstOrCreate(
                ['email' => $a['email']],
                [
                    'name' => $a['name'],
                    'email' => $a['email'],
                    'phone' => '09120000000',
                    'password' => Hash::make('password'),
                    'role' => UserRole::Ambassador->value,
                    'status' => 'active',
                ],
            );
            Wallet::firstOrCreate(['user_id' => $user->id]);

            AmbassadorProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'ig_username' => $a['ig'],
                    'ig_profile_url' => 'https://instagram.com/' . $a['ig'],
                    'category_id' => $a['category']?->id,
                    'province_id' => $a['province']?->id,
                    'city_id' => $a['city']?->id,
                    'group_id' => $a['group']?->id,
                    'followers_count' => $a['followers'],
                    'avg_views_7d' => $a['avg'],
                    'status' => ProfileStatus::Active->value,
                    'is_verified' => true,
                    'verified_at' => now(),
                ],
            );

            $ambassadorUsers[] = $user;
        }

        // کمپین نمونه فعال
        $campaign = Campaign::firstOrCreate(
            ['title' => 'کمپین معرفی محصول آرایشی'],
            [
                'advertiser_id' => $advertiser->id,
                'title' => 'کمپین معرفی محصول آرایشی',
                'description' => 'معرفی کرم ضدآفتاب جدید با تخفیف ویژه',
                'story_content' => 'لطفاً استوری معرفی محصول را منتشر کنید.',
                'category_id' => $category->id,
                'province_id' => $province->id,
                'city_id' => $city->id,
                'price_per_view' => 1000,
                'commission_rate' => 10,
                'capacity' => 100,
                'remaining_capacity' => 90,
                'min_avg_views' => 500,
                'status' => CampaignStatus::Active->value,
                'start_date' => now()->subDay(),
                'end_date' => now()->addDays(7),
            ],
        );

        // تخصیص نمونه و ثبت‌ویو
        if ($ambassadorUsers) {
            $assignment = CampaignAssignment::firstOrCreate(
                ['campaign_id' => $campaign->id, 'ambassador_id' => $ambassadorUsers[0]->id],
                [
                    'status' => AssignmentStatus::Accepted->value,
                    'assigned_at' => now()->subDay(),
                    'accepted_at' => now()->subDay(),
                ],
            );

            ViewSubmission::firstOrCreate(
                ['assignment_id' => $assignment->id],
                [
                    'campaign_id' => $campaign->id,
                    'ambassador_id' => $ambassadorUsers[0]->id,
                    'views_count' => 1200,
                    'status' => SubmissionStatus::Pending->value,
                    'submitted_at' => now(),
                ],
            );
        }
    }
}
