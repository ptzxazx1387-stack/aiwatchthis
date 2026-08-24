<?php

namespace App\Support;

use DateTimeInterface;

/**
 * تبدیل تاریخ میلادی به شمسی (هجری خورشیدی) بدون هیچ وابستگی خارجی.
 *
 * الگوریتم استاندارد تبدیل از طریق شمارش روز (Julian Day Number).
 */
final class Jalali
{
    private const MONTHS = [
        1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد',
        4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
        7 => 'مهر', 8 => 'آبان', 9 => 'آذر',
        10 => 'دی', 11 => 'بهمن', 12 => 'اسفند',
    ];

    private const WEEKDAYS = [
        0 => 'یکشنبه', 1 => 'دوشنبه', 2 => 'سه‌شنبه', 3 => 'چهارشنبه',
        4 => 'پنجشنبه', 5 => 'جمعه', 6 => 'شنبه',
    ];

    /**
     * @return array{0:int,1:int,2:int}  [سال، ماه، روز] شمسی
     */
    public static function fromGregorian(int $gy, int $gm, int $gd): array
    {
        $jdn = self::gregorianToJdn($gy, $gm, $gd);

        return self::jdnToJalali($jdn);
    }

    /**
     * نام ماه شمسی
     */
    public static function monthName(int $month): string
    {
        return self::MONTHS[$month] ?? '';
    }

    /**
     * قالب‌بندی یک تاریخ به شمسی.
     *
     * توکن‌های پشتیبانی‌شده:
     *   Y سال | m ماه دو رقمی | n ماه | d روز دو رقمی | j روز
     *   F نام ماه | l نام روز هفته | H ساعت | i دقیقه
     */
    public static function format(DateTimeInterface $date, string $pattern = 'Y/m/d'): string
    {
        [$jy, $jm, $jd] = self::fromGregorian(
            (int) $date->format('Y'),
            (int) $date->format('n'),
            (int) $date->format('j')
        );

        $map = [
            'Y' => (string) $jy,
            'm' => str_pad((string) $jm, 2, '0', STR_PAD_LEFT),
            'n' => (string) $jm,
            'd' => str_pad((string) $jd, 2, '0', STR_PAD_LEFT),
            'j' => (string) $jd,
            'F' => self::MONTHS[$jm],
            'l' => self::WEEKDAYS[(int) $date->format('w')],
            'H' => $date->format('H'),
            'i' => $date->format('i'),
        ];

        $out = '';
        $len = strlen($pattern);

        for ($i = 0; $i < $len; $i++) {
            $c = $pattern[$i];

            if ($c === '\\' && $i + 1 < $len) {
                $out .= $pattern[++$i];

                continue;
            }

            $out .= $map[$c] ?? $c;
        }

        return $out;
    }

    /* ------------------------------------------------------------------ */
    /*  هسته‌ی تبدیل                                                       */
    /* ------------------------------------------------------------------ */

    private static function gregorianToJdn(int $y, int $m, int $d): int
    {
        $a = intdiv(14 - $m, 12);
        $y2 = $y + 4800 - $a;
        $m2 = $m + 12 * $a - 3;

        return $d
            + intdiv(153 * $m2 + 2, 5)
            + 365 * $y2
            + intdiv($y2, 4)
            - intdiv($y2, 100)
            + intdiv($y2, 400)
            - 32045;
    }

    /**
     * @return array{0:int,1:int,2:int}
     */
    private static function jdnToJalali(int $jdn): array
    {
        // شمارش روزها از ابتدای سال ۴۷۵ هجری شمسی (مبدأ چرخه‌ی ۲۸۲۰ ساله)
        $depoch = $jdn - self::jalaliToJdn(475, 1, 1);
        $cycle = intdiv($depoch, 1029983);
        $cyear = $depoch % 1029983;

        if ($cyear === 1029982) {
            $ycycle = 2820;
        } else {
            $aux1 = intdiv($cyear, 366);
            $aux2 = $cyear % 366;
            $ycycle = intdiv(2134 * $aux1 + 2816 * $aux2 + 2815, 1028522) + $aux1 + 1;
        }

        $jy = $ycycle + 2820 * $cycle + 474;

        if ($jy <= 0) {
            $jy--;
        }

        $yday = $jdn - self::jalaliToJdn($jy, 1, 1) + 1;
        $jm = $yday <= 186 ? (int) ceil($yday / 31) : (int) ceil(($yday - 6) / 30);
        $jd = $jdn - self::jalaliToJdn($jy, $jm, 1) + 1;

        return [$jy, $jm, $jd];
    }

    private static function jalaliToJdn(int $jy, int $jm, int $jd): int
    {
        $epbase = $jy - ($jy >= 0 ? 474 : 473);
        $epyear = 474 + ($epbase % 2820);

        return $jd
            + ($jm <= 7 ? ($jm - 1) * 31 : ($jm - 1) * 30 + 6)
            + intdiv($epyear * 682 - 110, 2816)
            + ($epyear - 1) * 365
            + intdiv($epbase, 2820) * 1029983
            + (1948320 - 1);
    }
}
