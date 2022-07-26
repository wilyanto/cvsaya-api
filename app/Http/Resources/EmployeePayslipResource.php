<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePayslipResource extends JsonResource
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
            'status' => $this->status,
            'generated_at' => $this->generated_at,
            'generated_by' => $this->generated_by,
            'paid_at' => $this->paid_at,
            'paid_by' => $this->paid_by,
            'employee_id' => $this->employee_id,
            'payroll_period_id' => $this->payroll_period_id,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'payroll_period' => new PayrollPeriodResource($this->whenLoaded('payrollPeriod')),
            'payslip_details' => EmployeePayslipDetailResource::collection($this->whenLoaded('payslipDetails')),
            'payslip_ad_hocs' => EmployeePayslipAdHocResource::collection($this->whenLoaded('payslipAdHocs'))
        ];
    }
}
