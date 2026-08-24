<?php

namespace App\Support;

use Carbon\CarbonInterface;
use DateTimeInterface;

/**
 * قالب‌بندی‌های مشترک رابط کاربری: ارقام فارسی، مبلغ، تاریخ شمسی، «چند وقت پیش».
 *
 * همه‌ی خروجی‌ها متن ساده‌اند تا در Blade با {{ }} امن نمایش داده شوند.
 */
final class Fmt
{
    private const FA_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /**
     * تبدیل ارقام لاتین به فارسی
     */
    public static function fa(string|int|float|null $value): string
    {
        return strtr((string) $value, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);
    }

    /**
     * عدد با جداکننده‌ی هزارگان و ارقام فارسی
     */
    public static function num(int|float|string|null $value, int $decimals = 0): string
    {
        return self::fa(number_format((float) $value, $decimals));
    }

    /**
     * مبلغ به تومان — بدون واحد. واحد را جدا و کم‌رنگ کنار عدد بگذارید.
     */
    public static function money(int|float|string|null $value): string
    {
        return self::num($value);
    }

    /**
     * عددهای بزرگ به صورت خلاصه: ۱۲٫۴ هزار / ۳٫۱ میلیون
     */
    public static function compact(int|float|string|null $value): string
    {
        $n = (float) $value;

        if ($n >= 1_000_000) {
            return self::fa(rtrim(rtrim(number_format($n / 1_000_000, 1), '0'), '.')).' میلیون';
        }

        if ($n >= 1_000) {
            return self::fa(rtrim(rtrim(number_format($n / 1_000, 1), '0'), '.')).' هزار';
        }

        return self::num($n);
    }

    /**
     * درصد ایمن (بدون تقسیم بر صفر) — عددی بین ۰ و ۱۰۰
     */
    public static function percent(int|float $part, int|float $whole): float
    {
        if ($whole <= 0) {
            return 0.0;
        }

        return max(0.0, min(100.0, ($part / $whole) * 100));
    }

    /**
     * تاریخ شمسی، مثلاً «۱۴ مرداد ۱۴۰۴»
     */
    public static function date(?DateTimeInterface $date, string $pattern = 'j F Y'): string
    {
        if (! $date) {
            return '—';
        }

        return self::fa(Jalali::format($date, $pattern));
    }

    /**
     * تاریخ و ساعت شمسی، مثلاً «۱۴ مرداد ۱۴۰۴ · ۱۸:۳۰»
     */
    public static function dateTime(?DateTimeInterface $date): string
    {
        if (! $date) {
            return '—';
        }

        return self::fa(Jalali::format($date, 'j F Y').' · '.$date->format('H:i'));
    }

    /**
     * تاریخ فشرده برای جدول‌ها: «۱۴۰۴/۰۵/۱۴»
     */
    public static function shortDate(?DateTimeInterface $date): string
    {
        if (! $date) {
            return '—';
        }

        return self::fa(Jalali::format($date, 'Y/m/d'));
    }

    /**
     * فاصله‌ی زمانی به فارسی: «۳ ساعت پیش»
     */
    public static function ago(?DateTimeInterface $date): string
    {
        if (! $date) {
            return '—';
        }

        $seconds = time() - $date->getTimestamp();

        if ($seconds < 0) {
            return self::shortDate($date);
        }

        return match (true) {
            $seconds < 60 => 'همین حالا',
            $seconds < 3600 => self::fa(intdiv($seconds, 60)).' دقیقه پیش',
            $seconds < 86400 => self::fa(intdiv($seconds, 3600)).' ساعت پیش',
            $seconds < 604800 => self::fa(intdiv($seconds, 86400)).' روز پیش',
            default => self::shortDate($date),
        };
    }

    /**
     * حرف اول نام، برای آواتار مونوگرام
     */
    public static function initial(?string $name): string
    {
        $name = trim((string) $name);

        return $name === '' ? '؟' : mb_substr($name, 0, 1);
    }

    /**
     * شماره‌ی شبا خوانا: IR12 3456 7890 ...
     */
    public static function sheba(?string $sheba): string
    {
        $clean = strtoupper(preg_replace('/\s+/', '', (string) $sheba));

        return trim(chunk_split($clean, 4, ' '));
    }

    /**
     * @param  CarbonInterface|DateTimeInterface|null  $date
     */
    public static function relativeOrDate(mixed $date): string
    {
        return $date instanceof DateTimeInterface ? self::ago($date) : '—';
    }
}
