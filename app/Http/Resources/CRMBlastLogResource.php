<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CRMBlastLogResource extends JsonResource
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
            'country_code' => $this->recipient_country_code,
            'phone_number' => $this->recipient_phone_number,
            'blast_type_id' => $this->blast_type_id,
            'message' => $this->constructMessage(),
            'expired_at' => $this->expired_at,
            'priority' => $this->priority
        ];
    }
}
