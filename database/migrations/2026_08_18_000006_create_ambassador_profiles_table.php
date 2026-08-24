<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * پروفایل سفیران (دارندگان پیج اینستاگرام)
     */
    public function up(): void
    {
        Schema::create('ambassador_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ig_username')->unique();                // یوزرنیم اینستاگرام
            $table->string('ig_profile_url')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete(); // سطح ۱/۲/۳
            $table->unsignedBigInteger('followers_count')->default(0);
            $table->text('bio')->nullable();
            $table->unsignedInteger('avg_views_7d')->default(0);    // میانگین ویوی ۷ روز گذشته (کش)
            $table->string('status', 20)->default('pending');       // pending | active | suspended
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('avg_views_7d');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambassador_profiles');
    }
};
