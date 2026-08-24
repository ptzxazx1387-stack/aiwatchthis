<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * کمپین‌های تبلیغاتی
     */
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('story_content')->nullable();              // متن/محتوای استوری برای انتشار
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete(); // هدف جغرافیایی (اختیاری)
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->unsignedBigInteger('price_per_view')->default(0);   // هزینه هر ویو (تومان)
            $table->decimal('commission_rate', 5, 2)->nullable();        // درصد کمیسیون سامانه (null = مقدار پیش‌فرض)
            $table->unsignedInteger('capacity')->default(0);             // ظرفیت کل (تعداد ویو/جایگاه)
            $table->unsignedInteger('remaining_capacity')->default(0);   // ظرفیت باقیمانده
            $table->unsignedInteger('min_avg_views')->default(0);        // حداقل میانگین ویوی ۷ روزه برای سفیر
            $table->unsignedInteger('max_assignments_per_ambassador')->default(1);
            $table->string('status', 20)->default('draft'); // draft | pending | active | paused | completed | cancelled
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['status', 'start_date']);
            $table->index('advertiser_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
