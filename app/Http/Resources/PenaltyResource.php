<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenaltyResource extends JsonResource
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
            'amount' => $this->amount,
            'lateness' => $this->lateness,
            'attendance_type' => $this->attendance_type,
            'company_id' => $this->company_id,
            'company' => new CompanyResource($this->whenLoaded('company'))
        ];
    }
}
