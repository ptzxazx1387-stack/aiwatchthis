<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * کیف پول کاربران
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('balance', 15, 0)->default(0);        // موجودی قابل برداشت (تومان)
            $table->decimal('blocked_balance', 15, 0)->default(0); // موجودی مسدود (در انتظار تأیید)
            $table->string('currency', 8)->default('IRT');
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
