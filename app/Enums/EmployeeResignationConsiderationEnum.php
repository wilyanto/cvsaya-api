<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self fix()
 * @method static self considering()
 * @method static self planning()
 */

class EmployeeResignationConsiderationEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'fix' => 'fix',
            'considering' => 'considering',
            'planning' => 'planning',
        ];
    }
}
