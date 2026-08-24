<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تراکنش‌های کیف پول
     */
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 10);                            // credit | debit
            $table->decimal('amount', 15, 0);                       // مقدار (مثبت)
            $table->decimal('balance_after', 15, 0)->default(0);
            $table->string('reference_type', 30)->nullable();      // campaign_earning | withdrawal | commission | adjustment
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description')->nullable();
            $table->string('status', 20)->default('completed');    // pending | completed | failed
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index('reference_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
