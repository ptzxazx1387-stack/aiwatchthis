<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تخصیص کمپین به سفیران
     */
    public function up(): void
    {
        Schema::create('campaign_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('ambassador_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 20)->default('assigned'); // assigned | accepted | declined | completed
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('decline_reason')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'ambassador_id']);
            $table->index(['ambassador_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_assignments');
    }
};
