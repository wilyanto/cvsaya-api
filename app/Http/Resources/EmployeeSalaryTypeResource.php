<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeSalaryTypeResource extends JsonResource
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
            'employee_id' => $this->employee_id,
            'company_salary_type_id' => $this->company_salary_type_id,
            'amount' => $this->amount,
            'amount_type' => $this->amount_type,
            'company_salary_type' => new CompanySalaryTypeResource($this->whenLoaded('companySalaryType'))
        ];
    }
}
