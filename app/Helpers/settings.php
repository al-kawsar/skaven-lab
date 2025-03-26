<?php

if (!function_exists('settings')) {
    /**
     * Get a setting value from the database
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function settings($key, $default = null)
    {
        try {
            $setting = \App\Models\Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}