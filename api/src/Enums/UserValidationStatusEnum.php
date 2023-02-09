<?php

namespace App\Enums;

enum UserValidationStatusEnum: string {

    use EnumHelper;

    case ToComplete = 'to_complete';
    case ToReview = 'to_review';
    case Validated = 'validated';
}