<?php

namespace App\Enums;

enum ProfileStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار بررسی',
            self::Active => 'فعال',
            self::Suspended => 'معلق',
        };
    }

    /**
     * نام رنگ نشان (badge) در سیستم طراحی — هر کدام یک کار مشخص دارد.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Active => 'emerald',
            self::Suspended => 'rose',
        };
    }

    /**
     * @deprecated از <x-badge :tone="...->tone()"> استفاده کنید.
     */
    public function badgeClass(): string
    {
        return 'badge badge--'.$this->tone();
    }
}
