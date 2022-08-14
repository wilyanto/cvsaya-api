<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self unread()
 * @method static self read()
 */

class AnnouncementEmployeeStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'unread' => 'unread',
            'read' => 'read',
        ];
    }
}
