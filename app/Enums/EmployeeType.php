<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self daily()
 * @method static self monthly()
 */

class EmployeeType extends Enum
{
    const MAP_VALUE = [
        'daily' => 'daily',
        'monthly' => 'monthly',
    ];
}
