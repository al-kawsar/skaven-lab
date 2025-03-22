<?php

namespace App\Helpers;

class NumberFormatter
{
    public static function format($number)
    {
        if ($number >= 1000000000000) {
            return number_format($number / 1_000_000_000_000, 1) . 'T';
        }

        if ($number >= 1000000000) {
            return number_format($number / 1_000_000_000, 1) . 'M';
        }

        if ($number >= 1000000) {
            return number_format($number / 1_000_000, 1) . 'jt';
        }

        if ($number >= 1000) {
            return number_format($number / 1_000, 1) . 'rb';
        }

        return $number;
    }
}
