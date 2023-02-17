<?php

namespace App\Enums;

enum PropertyEnum: string
{
    use EnumHelper;

    case Availaible = 'availaible';
    case Busy = 'busy';
    case Locked = 'locked';
    case Viewing = 'viewing';
    case Rented = 'rented';

}
