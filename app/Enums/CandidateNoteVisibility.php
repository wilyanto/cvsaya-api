<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self private()
 * @method static self public()
 */

class CandidateNoteVisibility extends Enum
{
    const MAP_VALUE = [
        'private' => 'private',
        'public' => 'public',
    ];
}
