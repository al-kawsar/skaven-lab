<?php

namespace App\Helpers;

class ConvertStringHelper
{
    public static function convert($input)
    {
        $words = explode(' ', $input);

        if (count($words) <= 2) {
            return $input;
        }

        $acronym = '';
        if (count($words) > 3) {
            foreach ($words as $index => $word) {
                if ($index < count($words) - 1) {
                    $acronym .= strtoupper($word[0]);
                } else {
                    $acronym .= ' ' . ($word);
                }
            }
        } else {
            for ($i = 0; $i < count($words) - 1; $i++) {
                $acronym .= strtoupper($words[$i][0]);
            }
            $acronym .= '. ' . end($words);
        }

        return $acronym;
    }
}
