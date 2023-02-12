<?php

namespace App\Enums;

use Arr;

trait EnumHelper
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return \array_map(fn ($v) => $v->name, self::cases());
    }
}
