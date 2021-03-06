<?php

namespace App\Helpers;

class Format {

    const LONG_DATE = 'F j, Y';
    const SHORT_DATE = 'm/d/Y';

    public static function formatDate($mask, $date)
    {
        return (empty($date)) ? $date : date($mask, strtotime($date));
    }

    public static function formatMjY($date)
    {
        return self::formatDate('M j, Y', $date);
    }

    public static function formatDateForMySql($date)
    {
        $dateStamp = (is_string($date)) ? strtotime($date) : $date;
        return date("Y-m-d H:i:s", $dateStamp);
    }

    public static function formatPhone($phone_number)
    {
        $phone = null;
        if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $phone_number, $matches)) {
            $phone = '(' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
        }

        return $phone;
    }

    public static function rawPhone($phone)
    {
        return preg_replace('/[^0-9\/]+/', '', $phone);
    }
}