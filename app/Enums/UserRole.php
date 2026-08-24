<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Advertiser = 'advertiser';
    case Ambassador = 'ambassador';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'مدیر سیستم',
            self::Advertiser => 'تبلیغ‌دهنده',
            self::Ambassador => 'سفیر',
        };
    }

    /**
     * لیست نقش‌ها به‌صورت key => label برای استفاده در فرم‌ها
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $role) => [$role->value => $role->label()]
        )->all();
    }
}
