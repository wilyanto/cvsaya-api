<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self private()
 * @method static self public()
 */

class EmployeeType extends Enum
{
    const MAP_VALUE = [
        'daily' => 'daily',
        'monthly' => 'monthly',
    ];
}
