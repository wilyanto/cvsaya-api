<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self clockIn()
 * @method static self clockOut()
 * @method static self breakStartedAt()
 * @method static self breakEndedAt()
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
            'breakStartedAt' => 'break_started_at',
            'breakEndedAt' => 'break_ended_at',
            'breakDuration' => 'break_duration',
            'breakTime' => 'break_time'
        ];
    }
}
