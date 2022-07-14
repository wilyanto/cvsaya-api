<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self cancelled()
 * @method static self pending()
 * @method static self acknowledged()
 */

class EmployeeResignationStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'cancelled' => 'cancelled',
            'pending' => 'pending',
            'acknowledged' => 'acknowledged',
        ];
    }
}
