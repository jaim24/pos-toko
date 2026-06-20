<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'label', 'group'];

    /** Get setting value by key */
    public static function get(string $key, $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /** Get all settings as key-value array */
    public static function allAsArray(): array
    {
        return static::pluck('value', 'key')->toArray();
    }

    /** Get settings by group */
    public static function byGroup(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->all();
    }
}
