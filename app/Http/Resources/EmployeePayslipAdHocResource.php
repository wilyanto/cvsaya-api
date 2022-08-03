<?php

namespace App\Http\Resources;

use App\Models\EmployeeAdHoc;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePayslipAdHocResource extends JsonResource
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
            'date' => $this->date,
            'amount' => $this->amount,
            'note' => $this->note,
            'company_salary_type_id' => $this->company_salary_type_id,
            'company_salary_type' => new CompanySalaryTypeResource($this->whenLoaded('companySalaryType')),
            'employee_payslip_id' => $this->employee_payslip_id,
            'payslip' => new EmployeePayslipResource($this->whenLoaded('payslip')),
        ];
    }
}
