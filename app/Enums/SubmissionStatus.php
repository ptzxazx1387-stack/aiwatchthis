<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار تأیید',
            self::Approved => 'تأییدشده',
            self::Rejected => 'ردشده',
        };
    }

    /**
     * نام رنگ نشان (badge) در سیستم طراحی — هر کدام یک کار مشخص دارد.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Approved => 'emerald',
            self::Rejected => 'rose',
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
