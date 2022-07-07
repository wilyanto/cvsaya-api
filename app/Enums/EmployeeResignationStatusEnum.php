<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self declined()
 * @method static self pending()
 * @method static self accepted()
 */

class EmployeeResignationStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'declined' => 'declined',
            'pending' => 'pending',
            'accepted' => 'accepted',
        ];
    }
}
