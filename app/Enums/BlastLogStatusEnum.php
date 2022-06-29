<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self pending()
 * @method static self sent()
 * @method static self failed()
 */

class BlastLogStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'pending' => 'pending',
            'sent' => 'sent',
            'failed' => 'failed',
        ];
    }
}
