<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('settings.all');
        });
    }

    /**
     * خواندن یک تنظیم با مقدار پیش‌فرض
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::allCached();

        if (! array_key_exists($key, $all)) {
            return $default;
        }

        return match ($all[$key]['type'] ?? 'string') {
            'integer' => (int) $all[$key]['value'],
            'boolean' => (bool) $all[$key]['value'],
            'json' => json_decode($all[$key]['value'], true),
            default => $all[$key]['value'],
        };
    }

    /**
     * ثبت/به‌روزرسانی یک تنظیم
     */
    public static function set(string $key, mixed $value, string $type = 'string', string $group = 'general', ?string $label = null): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'type' => $type, 'group' => $group, 'label' => $label]
        );
    }

    protected static function allCached(): array
    {
        return Cache::rememberForever('settings.all', function () {
            return self::all()->keyBy('key')->map(
                fn ($s) => ['value' => $s->value, 'type' => $s->type]
            )->all();
        });
    }
}
