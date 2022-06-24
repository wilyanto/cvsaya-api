<?php

namespace App\Http\Resources;

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
            'phone_number' => $this->phone_number
        ];
    }
}
