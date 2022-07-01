<?php

namespace App\Services;

use App\Models\CRMCredentialQuotaType;
use App\Models\QuotaType;
use Illuminate\Support\Facades\Http;

class CRMCredentialQuotaTypeService
{
    /**
     * Get all Credential Quota Type by credential id.
     *
     * @param int $credentialId
     * @return \App\Models\CRMCredentialQuotaType
     */
    public function getAllCRMCredentialQuotaTypeByCredentialId($credentialId)
    {
        $CRMCredentialQuotaTypes = CRMCredentialQuotaType::where('credential_id', $credentialId)->get();

        return $CRMCredentialQuotaTypes;
    }

    public function getCRMCredentialQuotaTypeByCredentialIdAndQuotaType($credentialId, $quotaType)
    {
        $quotaType = QuotaType::find($quotaType->quota_type_id);
        $CRMCredentialQuotaType = CRMCredentialQuotaType::whereHas('quotaType', function ($query) use ($quotaType) {
            $query->where('name', $quotaType->name);
        })->first();

        return $CRMCredentialQuotaType;
    }

    /**
     * Create Credential Quota Type by credential id and array of data.
     *
     * @param array $data
     * @param int $credentialId
     * @return \App\Models\CRMCredentialQuotaType
     */

    public function createByCredentialIdAndData($credentialId, $data)
    {
        $CRMCredentialQuotaTypes = [];
        foreach ($data as $datum) {
            $CRMCredentialQuotaType = CRMCredentialQuotaType::create([
                'credential_id' => $credentialId,
                'blast_type_id' => $datum->blast_type_id,
                'last_updated_at' => $datum->last_updated_at,
                'remaining' => $datum->remaining,
                'quota' => $datum->quota,
            ]);

            array_push($CRMCredentialQuotaTypes, $CRMCredentialQuotaType);
        }

        return $CRMCredentialQuotaTypes;
    }

    /**
     * Delete Credential Quota Type by credential id.
     *
     * @param int $credentialId
     * @return bool
     */
    public function deleteByCredentialId($credentialId)
    {
        $CRMCredentialQuotaTypes = $this->getAllCRMCredentialQuotaTypeByCredentialId($credentialId);
        foreach ($CRMCredentialQuotaTypes as $CRMCredentialQuotaType) {
            $CRMCredentialQuotaType->delete();
        }

        return true;
    }

    public function syncAllCredentialQuotaType($credentials)
    {
        $credentialQuotaTypes = [];
        foreach ($credentials as $credential) {
            array_push($credentialQuotaTypes, $this->syncCredentialQuotaType($credential->id, $credential->key));
        }

        return $credentialQuotaTypes;
    }

    public function syncCredentialQuotaType($credentialId, $key)
    {
        $syncCredentialQuotaTypes = $this->syncCredentialQuota($key);
        $credentialQuotaTypes = [];
        foreach ($syncCredentialQuotaTypes as $syncCredentialQuotaType) {
            // encode and decode to change array back to object
            $syncCredentialQuotaType = json_decode(json_encode($syncCredentialQuotaType));
            $credentialQuotaType = $this->getCRMCredentialQuotaTypeByCredentialIdAndQuotaType($credentialId, $syncCredentialQuotaType);

            if ($credentialQuotaType) {
                $credentialQuotaType->update([
                    'last_updated_at' => now(),
                    'remaining' => $syncCredentialQuotaType->remaining_quota,
                    'quota' => $syncCredentialQuotaType->remaining_quota, // TODO: get from total instead
                ]);
            } else {
                $credentialQuotaType = CRMCredentialQuotaType::create([
                    'credential_id' => $credentialId,
                    'quota_type_id' => $syncCredentialQuotaType->quota_type_id,
                    'last_updated_at' => now(),
                    'remaining' => $syncCredentialQuotaType->remaining_quota,
                    'quota' => $syncCredentialQuotaType->remaining_quota,
                ]);
            }
            array_push($credentialQuotaTypes, $credentialQuotaType);
        }

        return $credentialQuotaTypes;
    }

    public function syncCredentialQuota($key)
    {
        $url = env('ECRM_URL') . "/api/v1/whatsapp-devices/$key/quotas";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
            ->acceptJson()
            ->get($url);
        $data = json_decode($response->body(), true)['data'];
        return $data;
    }
}