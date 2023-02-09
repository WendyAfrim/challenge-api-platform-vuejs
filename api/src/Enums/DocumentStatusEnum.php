<?php

namespace App\Enums;

enum DocumentStatusEnum: string {

    use EnumHelper;

    case ToReview = 'to_review';
    case Validated = 'validated';
    case Rejected = 'rejected';
}