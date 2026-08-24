<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * گروه‌های کاربری (سطوح ۱، ۲ و ۳) با محدودیت دریافت تبلیغ
     */
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // مثلاً «سطح ۱»
            $table->string('code')->unique();           // مثلاً level-1
            $table->unsignedInteger('daily_campaign_limit')->default(1);   // حداکثر تبلیغ در روز
            $table->unsignedInteger('weekly_campaign_limit')->default(5);  // حداکثر تبلیغ در هفته
            $table->unsignedInteger('min_avg_views')->default(0);          // حداقل میانگین ویو برای این سطح
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
