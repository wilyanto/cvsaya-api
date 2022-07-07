<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self fix()
 * @method static self half()
 * @method static self thinking()
 */

class EmployeeResignationConsiderationEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'fix' => 'fix',
            'half' => 'half',
            'thinking' => 'thinking',
        ];
    }
}
