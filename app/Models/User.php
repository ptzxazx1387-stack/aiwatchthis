<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'status', 'national_code', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Helper Methods                                                     */
    /* ------------------------------------------------------------------ */

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isAdvertiser(): bool
    {
        return $this->role === UserRole::Advertiser;
    }

    public function isAmbassador(): bool
    {
        return $this->role === UserRole::Ambassador;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * مسیر داشبورد متناسب با نقش کاربر (برای ریدایرکت بعد از ورود)
     */
    public function dashboardRoute(): string
    {
        return match ($this->role) {
            UserRole::Admin => 'admin.dashboard',
            UserRole::Advertiser => 'advertiser.dashboard',
            UserRole::Ambassador => 'ambassador.dashboard',
        };
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function ambassadorProfile(): HasOne
    {
        return $this->hasOne(AmbassadorProfile::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'advertiser_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(CampaignAssignment::class, 'ambassador_id');
    }

    public function viewSubmissions(): HasMany
    {
        return $this->hasMany(ViewSubmission::class, 'ambassador_id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * اعلان‌های خوانده‌نشده
     */
    public function unreadNotifications(): HasMany
    {
        return $this->notifications()->whereNull('read_at');
    }
}
