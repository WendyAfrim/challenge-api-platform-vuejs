<?php

namespace App\Enums;

enum AvailaibilityEnum: string
{
    use EnumHelper;

    case Pending = 'pending';
    case Accepted = 'accepted';
    case Refused = 'refused';
}
