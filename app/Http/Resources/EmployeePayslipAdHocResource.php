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
            'employee_payslip_id' => $this->employee_payslip_id,
            'employee_ad_hoc_id' => $this->employee_ad_hoc_id,
            'payslip' => new EmployeePayslipResource($this->whenLoaded('payslip')),
            'employee_ad_hoc' => new EmployeeAdHocResource($this->whenLoaded('employeeAdHoc'))
        ];
    }
}
