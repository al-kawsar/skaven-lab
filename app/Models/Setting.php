<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'value', 'group', 'type', 'options', 'label'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array'
    ];

    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        // Try to get from cache first
        $cachedSettings = Cache::get('app_settings');

        if ($cachedSettings && isset($cachedSettings[$key])) {
            return $cachedSettings[$key];
        }

        // If not in cache, fetch from database
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return \App\Models\Setting
     */
    public static function set($key, $value)
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            $setting = self::create(['key' => $key, 'value' => $value]);
        }

        // Clear the cache
        Cache::forget('app_settings');

        return $setting;
    }

    /**
     * Get all settings as key-value pairs
     *
     * @return array
     */
    public static function getAllSettings()
    {
        return Cache::remember('app_settings', 86400, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get settings by group
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get();
    }

    /**
     * Clear the settings cache
     */
    public static function clearCache()
    {
        Cache::forget('app_settings');
    }
}
