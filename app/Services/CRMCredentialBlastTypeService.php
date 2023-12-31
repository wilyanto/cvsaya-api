<?php

namespace App\Services;

use App\Models\CRMCredentialBlastType;

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
        $CRMCredentialBlastType = CRMCredentialBlastType::where('credential_id', $credentialId)
            ->latest()
            ->first();

        if ($CRMCredentialBlastType) {
            $lastPriorityNumber = $CRMCredentialBlastType->priority + 1;
        } else {
            $lastPriorityNumber = 1;
        }

        $CRMCredentialBlastTypes = [];
        foreach ($data as $blastTypeId) {
            $CRMCredentialBlastType = CRMCredentialBlastType::create([
                'credential_id' => $credentialId,
                'blast_type_id' => $blastTypeId,
                'priority' => $lastPriorityNumber++
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

    public function updateByCredentialId($credentialId, $data)
    {
        $this->deleteByCredentialId($credentialId);
        $CRMCredentialBlastTypes = [];
        foreach ($data as $datum) {
            $CRMCredentialBlastType = CRMCredentialBlastType::create([
                'credential_id' => $credentialId,
                'blast_type_id' => $datum['blast_type_id'],
                'priority' => $datum['priority']
            ]);

            array_push($CRMCredentialBlastTypes, $CRMCredentialBlastType);
        }

        return $CRMCredentialBlastTypes;
    }
}
