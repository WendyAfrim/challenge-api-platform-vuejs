<?php

namespace App\Enums;

enum WorkSituationEnum: string {

    use EnumHelper;

    case Employee = 'employee';
    case PublicOfficial = 'public_official';
    case AlterningStudent = 'alternating_student';
    case Student = 'student';
}