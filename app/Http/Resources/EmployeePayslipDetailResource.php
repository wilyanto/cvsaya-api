<?php

namespace App\Http\Resources;

use App\Models\CompanySalaryType;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePayslipDetailResource extends JsonResource
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
            'note' => $this->note,
            'company_salary_type_id' => $this->company_salary_type_id,
            'company_salary_type' => new CompanySalaryTypeResource($this->whenLoaded('companySalaryType'))
        ];
    }
}
