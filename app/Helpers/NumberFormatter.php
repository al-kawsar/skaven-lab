<?php

namespace App\Helpers;

class NumberFormatter
{
    public static function format($number)
    {
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'jt'; // Format untuk juta
        }

        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'rb'; // Format untuk ribu
        }

        return $number; // Jika kurang dari 1000, tampilkan angka aslinya
    }
}
