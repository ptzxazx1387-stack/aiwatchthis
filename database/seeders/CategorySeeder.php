<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * دسته‌بندی‌های پیج و کسب‌وکار
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'زیبایی و آرایشی', 'slug' => 'beauty'],
            ['name' => 'مد و پوشاک', 'slug' => 'fashion'],
            ['name' => 'غذا و رستوران', 'slug' => 'food'],
            ['name' => 'تکنولوژی و گجت', 'slug' => 'technology'],
            ['name' => 'ورزش و تناسب اندام', 'slug' => 'sport'],
            ['name' => 'آموزش و تحصیل', 'slug' => 'education'],
            ['name' => 'سلامت و درمان', 'slug' => 'health'],
            ['name' => 'سفر و گردشگری', 'slug' => 'travel'],
            ['name' => 'موسیقی و هنر', 'slug' => 'art'],
            ['name' => 'خودرو', 'slug' => 'automotive'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
