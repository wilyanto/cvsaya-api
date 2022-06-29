<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self interviewReminder()
 * @method static self completenessReminder()
 * @method static self leavePermissionNotification()
 * @method static self payslipNotification()
 */

class BlastTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'interviewReminder' => 'interview_reminder',
            'completenessReminder' => 'completeness_reminder',
            'leavePermissionNotification' => 'leave_permission_notification',
            'payslipNotification' => 'payslip_notification',
        ];
    }
}
