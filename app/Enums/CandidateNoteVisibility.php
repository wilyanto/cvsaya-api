<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self public()
 * @method static self private()
 */
class CandidateNoteVisibility extends Enum
{
    const MAP_VALUE = [
        'public' => 'PUBLIC',
        'private' => 'PRIVATE',
    ];
}
