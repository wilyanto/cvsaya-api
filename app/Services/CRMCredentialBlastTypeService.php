<?php

namespace App\Services;

use App\Models\CRMCredential;
use App\Models\CRMCredentialBlastType;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class CRMCredentialBlastTypeService
{
    /**
     * Get all Credential Blast Type by credential id.
     *
     * @param int $credentialId
     * @return \App\Models\CRMCredentialBlastType
     */
    public function getAllCRMCredentialBlastTypeByCredentialId($credentialId)
    {
        $CRMCredentialBlastTypes = CRMCredentialBlastType::where('credential_id', $credentialId)->get();

        return $CRMCredentialBlastTypes;
    }

    /**
     * Create Credential Blast Type by credential id and array of data.
     *
     * @param array $data
     * @param int $credentialId
     * @return \App\Models\CRMCredentialBlastType
     */

    public function createByCredentialIdAndData($credentialId, $data)
    {
        $CRMCredentialBlastTypes = [];
        foreach ($data as $blastTypeId) {
            $CRMCredentialBlastType = CRMCredentialBlastType::create([
                'credential_id' => $credentialId,
                'blast_type_id' => $blastTypeId
            ]);

            array_push($CRMCredentialBlastTypes, $CRMCredentialBlastType);
        }

        return $CRMCredentialBlastTypes;
    }

    /**
     * Delete Credential Blast Type by credential id.
     *
     * @param int $credentialId
     * @return bool
     */
    public function deleteByCredentialId($credentialId)
    {
        $CRMCredentialBlastTypes = $this->getAllCRMCredentialBlastTypeByCredentialId($credentialId);
        foreach ($CRMCredentialBlastTypes as $CRMCredentialBlastType) {
            $CRMCredentialBlastType->delete();
        }

        return true;
    }
}
