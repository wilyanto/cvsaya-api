<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self pending()
 * @method static self accepted()
 * @method static self rejected()
 */

class EarlyClockOutAttendanceStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'pending' => 'pending',
            'accepted' => 'accepted',
            'rejected' => 'rejected',
        ];
    }
}
