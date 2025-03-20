<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format tanggal ke Bahasa Indonesia
     *
     * @param string|Carbon $date
     * @param string $format Default: 'dddd, D MMMM YYYY'
     * @return string
     */
    public static function formatIndonesian($date, $format = 'dddd, D MMMM YYYY')
    {
        if (!$date) {
            return '-';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->locale('id')->isoFormat($format);
    }

    /**
     * Format tanggal singkat ke Bahasa Indonesia
     *
     * @param string|Carbon $date
     * @return string
     */
    public static function formatShort($date)
    {
        return self::formatIndonesian($date, 'D, D MMM YYYY');
    }

    /**
     * Format tanggal panjang ke Bahasa Indonesia
     *
     * @param string|Carbon $date
     * @return string
     */
    public static function formatLong($date)
    {
        return self::formatIndonesian($date, 'dddd, D MMMM YYYY');
    }
}