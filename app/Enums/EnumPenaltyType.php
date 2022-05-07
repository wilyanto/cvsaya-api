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
    const MAP_VALUE = [
        'clockIn' => 'clock_in',
        'clockOut' => 'clock_out',
        'breakTime' => 'break_time',
    ];
}
