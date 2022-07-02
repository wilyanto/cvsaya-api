<?php

namespace App\Http\Resources;

use App\Models\BlastType;
use Illuminate\Http\Resources\Json\JsonResource;

class CRMCredentialResource extends JsonResource
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
            'name' => $this->name,
            'key' => $this->key,
            'country_code' => $this->country_code,
            'phone_number' => $this->phone_number,
            'uuid' => $this->uuid,
            'sent_messages_count' => $this->getTodayBlastLogCount(),
            'is_active' => $this->is_active,
            'expired_at' => $this->expired_at,
            'scheduled_message_count' => $this->scheduled_message_count,
            'last_updated_at' => $this->last_updated_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'blast_type_count' => $this->getBlastTypeCount(),
            'blast_types' => BlastTypeResource::collection($this->whenLoaded('blastTypes')),
            'quotas' => CRMCredentialQuotaTypeResource::collection($this->whenLoaded('quotas')),
            'blast_logs' => CRMBlastLogResource::collection($this->whenLoaded('blastLogs')),
            'recent_messages' => CRMBlastLogResource::collection($this->whenLoaded('recentMessages'))
        ];
    }
}
