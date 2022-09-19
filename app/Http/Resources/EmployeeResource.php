<?php

namespace App\Http\Resources;

use App\Models\EmployeeSalaryType;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'candidate_id' => $this->candidate_id,
            'position_id' => $this->position_id,
            'type' => $this->type,
            'is_default' => $this->is_default,
            'status' => $this->status,
            'joined_at' => $this->joined_at,
            'position' => $this->position,
            'candidate' => $this->candidate,
            'company' => $this->company,
            'bank_account' => $this->bankAccount,
            'employee_salary_types' => EmployeeSalaryTypeResource::collection($this->whenLoaded('employeeSalaryTypes'))
        ];
    }
}
