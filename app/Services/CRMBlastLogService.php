<?php

namespace App\Services;

use App\Models\CRMBlastLog;

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
            'priority' => $blastType->priority,
            'expired_at' => now()
        ]);
        $CRMBlastLog->blastLoggable()->associate($candidate)->save();

        return $CRMBlastLog;
    }

    public function getBlastLogByCredentialId($credentialId, $size)
    {
        $CRMBlastLogs = CRMBlastLog::where('credential_id', $credentialId)->latest()->paginate($size);

        return $CRMBlastLogs;
    }
}
