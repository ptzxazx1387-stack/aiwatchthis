<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * ایجاد کاربر مدیر سیستم
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'مدیر سیستم',
                'email' => 'admin@example.com',
                'phone' => '09120000000',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin->value,
                'status' => 'active',
            ],
        );

        Wallet::firstOrCreate(['user_id' => $admin->id]);
    }
}
