<?php

namespace App\Services;

use App\Models\CRMBlastLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CRMBlastLogService
{
    public function create($candidate, $credential, $blastType, $messageParamValue, $messageTemplate)
    {
        $CRMBlastLog = new CRMBlastLog([
            'blast_loggable_id' => $candidate->id,
            'credential_id' => $credential->id,
            'recipient_country_code' => $candidate->country_code,
            'recipient_phone_number' => $candidate->phone_number,
            'blast_type_id' => $blastType->id,
            'message_param_value' => json_encode($messageParamValue),
            'message_template' => json_encode($messageTemplate),
        ]);
        $CRMBlastLog->blastLoggable()->associate($candidate)->save();
    }
}
