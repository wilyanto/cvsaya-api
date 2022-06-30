<?php

namespace App\Services;

use App\Models\CRMCredentialQuotaType;

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
}
