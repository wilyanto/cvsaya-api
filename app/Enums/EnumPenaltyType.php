<?php

namespace App\Enums;

enum EnumPenaltyType: string
{
    case ClockIn = 'clock_in';
    case ClockOut = 'clock_out';
    case BreakTime = 'break_time';
}