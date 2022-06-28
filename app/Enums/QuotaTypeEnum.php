<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self daily()
 * @method static self weekly()
 * @method static self monthly()
 * @method static self weekday()
 * @method static self weekend()
 * @method static self morning()
 * @method static self afternoon()
 * @method static self evening()
 * @method static self midnight()
 * @method static self officeHour()
 */

class QuotaTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'daily' => 'daily',
            'weekly' => 'weekly',
            'monthly' => 'monthly',
            'weekday' => 'weekday',
            'weekend' => 'weekend',
            'morning' => 'morning',
            'afternoon' => 'afternoon',
            'evening' => 'evening',
            'midnight' => 'midnight',
            'officeHour' => 'office_hour',
        ];
    }
}
