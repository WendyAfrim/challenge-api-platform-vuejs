<?php

namespace App\Helpers;

class DateFormatterHelper
{
    public static function stringToDatetime(string $date): ?\DateTimeInterface
    {
        return new \Datetime($date);
    }
}
