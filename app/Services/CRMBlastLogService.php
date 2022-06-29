<?php

namespace App\Services;

use App\Enums\BlastLogStatusEnum;
use App\Http\Common\Filter\FilterBlastLogDateRange;
use App\Http\Common\Filter\FilterBlastLogSearch;
use App\Models\CRMBlastLog;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;

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
            'status' => BlastLogStatusEnum::pending(),
            'expired_at' => now()
        ]);
        $CRMBlastLog->blastLoggable()->associate($candidate)->save();

        return $CRMBlastLog;
    }

    public function getBlastLogByCredentialId($credentialId, $size)
    {
        $CRMBlastLogs = QueryBuilder::for(CRMBlastLog::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterBlastLogSearch),
                AllowedFilter::custom('date-between', new FilterBlastLogDateRange),
            ])
            ->where('credential_id', $credentialId)
            ->latest()
            ->paginate($size);
        return $CRMBlastLogs;
    }
}
