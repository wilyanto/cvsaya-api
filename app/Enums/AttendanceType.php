<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self clockIn()
 * @method static self clockOut()
 * @method static self startBreak()
 * @method static self endBreak()
 * @method static self breakDuration()
 * @method static self breakTime()
 */

class AttendanceType extends Enum
{
    protected static function values(): array
    {
        return [
            'clockIn' => 'clock_in',
            'clockOut' => 'clock_out',
            'startBreak' => 'start_break',
            'endBreak' => 'end_break',
            'breakDuration' => 'break_duration',
            'breakTime' => 'break_time'
        ];
    }
}
