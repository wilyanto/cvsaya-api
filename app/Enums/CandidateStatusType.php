<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self daily()
 * @method static self monthly()
 */

class CandidateStatusType extends Enum
{
    protected static function values(): array
    {
        return [
            'lulus' => 'lulus',
            'tidakLulus' => 'tidak lulus',
            'dipertimbangkan' => 'dipertimbangkan',
            'dicadangkan' => 'dicadangkan',
            'tidakDisarankan' => 'tidak disarankan'
        ];
    }
}
