<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * افزودن ستون‌های نقش، موبایل، وضعیت و ... به جدول کاربران
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('role', 20)->default('ambassador')->after('phone'); // admin | advertiser | ambassador
            $table->string('status', 20)->default('active')->after('role');    // active | suspended
            $table->string('national_code', 10)->nullable()->after('status');
            $table->string('avatar')->nullable()->after('national_code');

            $table->index(['role', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'status', 'national_code', 'avatar']);
        });
    }
};
