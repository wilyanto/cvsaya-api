<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self clockIn()
 * @method static self clockOut()
 * @method static self breakTime()
 */

class EnumPenaltyType extends Enum
{
    protected static function values(): array
    {
        return [
            'clockIn' => 'clock_in',
            'clockOut' => 'clock_out',
            'breakTime' => 'break_time',
        ];
    }
}
