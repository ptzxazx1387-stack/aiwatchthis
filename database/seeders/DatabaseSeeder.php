<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * اجرای همه سیدرها
     */
    public function run(): void
    {
        $this->call([
            GroupSeeder::class,
            CategorySeeder::class,
            ProvinceCitySeeder::class,
            SettingSeeder::class,
            AdminSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
