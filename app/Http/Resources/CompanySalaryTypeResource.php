<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanySalaryTypeResource extends JsonResource
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
            'company_id' => $this->company_id,
            'salary_type_id' => $this->salary_type_id,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'salary_type' => new SalaryTypeResource($this->whenLoaded('salaryType')),
        ];
    }
}
