<?php

namespace App\Helpers;

class Date {

    public static function calendarYearList($startYear, $length = 20)
    {
        $range = range(intval($startYear), intval($startYear) + $length);
        return array_combine($range, $range);
    }
}