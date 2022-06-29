<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CRMCredentialBlastTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'credential_id' => $this->credential_id,
            'blast_type_id' => $this->blast_type_id,
            'priority' => $this->priority,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
