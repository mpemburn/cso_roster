<?php

namespace App\Helpers;

class Date {

    public static function calendarYearList($startYear, $length = 20, $prepend = null)
    {
        $keys = range(intval($startYear), intval($startYear) + $length);
        $values = $keys;
        if (is_array($prepend)) {
            array_unshift($keys, $prepend[1]);
            array_unshift($values, $prepend[0]);
        }
        return array_combine($keys, $values);
    }
}