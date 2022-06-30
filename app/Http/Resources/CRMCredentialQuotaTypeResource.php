<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CRMCredentialQuotaTypeResource extends JsonResource
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
            'quota_type_id' => $this->quota_type_id,
            'quota_type' => new QuotaTypeResource($this->whenLoaded('quotaType')),
            'remaining' => $this->remaining,
            'quota' => $this->quota,
            'last_updated_at' => $this->last_updated_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
