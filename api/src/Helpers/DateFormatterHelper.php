<?php

namespace App\Helpers;

class DateFormatterHelper
{
    public static function stringToDatetime(string $date): ?\DateTimeInterface
    {
        return new \Datetime($date);
    }

    public static function dateTimeToString(\DateTimeInterface $date): ?string
    {
        return $date->format('d-m-Y H:i:s');
    }
}
