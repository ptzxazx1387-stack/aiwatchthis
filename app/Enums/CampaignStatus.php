<?php

namespace App\Enums;

enum CampaignStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Active = 'active';
    case Paused = 'paused';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'پیش‌نویس',
            self::Pending => 'در انتظار تأیید',
            self::Active => 'فعال',
            self::Paused => 'متوقف',
            self::Completed => 'تکمیل‌شده',
            self::Cancelled => 'لغو‌شده',
        };
    }

    /**
     * نام رنگ نشان (badge) در سیستم طراحی — هر کدام یک کار مشخص دارد.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Draft => 'mist',
            self::Pending => 'amber',
            self::Active => 'emerald',
            self::Paused => 'sky',
            self::Completed => 'ink',
            self::Cancelled => 'rose',
        };
    }

    /**
     * @deprecated از <x-badge :tone="...->tone()"> استفاده کنید.
     */
    public function badgeClass(): string
    {
        return 'badge badge--'.$this->tone();
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $status) => [$status->value => $status->label()]
        )->all();
    }
}
