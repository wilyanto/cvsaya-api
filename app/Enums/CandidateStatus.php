<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum CandidateSatus: string
{
    case Accepted = "Accept";

    case Inputing_CvSaya = "Input";

    case Ready = "Ready";

    case Interview = "Interview";

    case StandBy = "Standby";

    case Consider = "consider";

    case Decline = "Decline";
}
