<?php

namespace App\Enums;

enum DocumentTypeEnum: string {

    use EnumHelper;

    case Identity = 'identity';
    case Address = 'address';
    case Professional = 'professional';
    case Income = 'income';
    case TaxStatus = 'tax_status';
}