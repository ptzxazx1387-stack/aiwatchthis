<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * دسته‌بندی پیج‌ها و کسب‌وکارها (مثلاً زیبایی، غذا، تکنولوژی و ...)
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // نام فارسی
            $table->string('slug')->unique();       // اسلاگ انگلیسی برای لینک‌ها
            $table->string('icon')->nullable();     // نام آیکون
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
