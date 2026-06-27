<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Setting extends Model implements HasMedia, Auditable
{
    use HasTranslations, InteractsWithMedia,AuditableTrait;

    protected $fillable = ['key', 'value', 'translatable_value'];

    public $translatable = ['translatable_value'];

    public static function getValue($key, $locale = null, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) return $default;

        if ($setting->translatable_value) {
            if (method_exists($setting, 'getTranslation')) {
                return $setting->getTranslation('translatable_value', $locale ?? app()->getLocale());
            } else {
                $decoded = json_decode($setting->translatable_value, true);
                return $decoded[$locale ?? app()->getLocale()] ?? $default;
            }
        }

        return $setting->value ?? $default;
    }

    public static function setValue($key, $value, $isTranslatable = false)
    {
        if ($isTranslatable) {
            return self::updateOrCreate(
                ['key' => $key],
                ['translatable_value' => $value, 'value' => null]
            );
        }
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'translatable_value' => null]
        );
    }


    public static function getMediaUrl($collectionName, $conversion = '')
    {
        $setting = self::where('key', 'media')->first();
        return $setting ? $setting->getFirstMediaUrl($collectionName, $conversion) : null;
    }
}

