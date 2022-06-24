<?php

namespace App\Services;

use App\Models\CRMBlastLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CRMBlastLogService
{
    public function create($candidate)
    {
        $CRMBlastLog = CRMBlastLog::create([
            'model_type' => 'model_type',
            'model_id' => $candidate->id,
            'sender_country_code' => 'sender_country_code',
            'sender_phone_number' => 'sender_phone_number',
            'recipient_country_code' => $candidate->country_code,
            'recipient_phone_number' => $candidate->phone_number,
            'blast_type_id' => 'blast_type_id',
            'message_variable' => 'message_variable',
            'message_template' => 'message_template',
        ]);
        $CRMBlastLog->CRMblastLoggable()->associate($candidate);
    }
}
