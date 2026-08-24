<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ثبت ویو و اسکرین‌شات توسط سفیر
     */
    public function up(): void
    {
        Schema::create('view_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('campaign_assignments')->cascadeOnDelete();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('ambassador_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('views_count')->default(0);        // تعداد ویو ثبت‌شده
            $table->string('screenshot_path')->nullable();             // آپلود اسکرین‌شات
            $table->text('description')->nullable();
            $table->string('status', 20)->default('pending');          // pending | approved | rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['ambassador_id', 'status']);
            $table->index(['campaign_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('view_submissions');
    }
};
