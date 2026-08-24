<?php

namespace App\Enums;

enum AssignmentStatus: string
{
    case Assigned = 'assigned';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Assigned => 'تخصیص‌یافته',
            self::Accepted => 'پذیرفته‌شده',
            self::Declined => 'ردشده',
            self::Completed => 'تکمیل‌شده',
        };
    }

    /**
     * نام رنگ نشان (badge) در سیستم طراحی — هر کدام یک کار مشخص دارد.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Assigned => 'amber',
            self::Accepted => 'sky',
            self::Declined => 'rose',
            self::Completed => 'emerald',
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
