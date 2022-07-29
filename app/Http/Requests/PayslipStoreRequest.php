<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayslipStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'payroll_period_id' => 'required|exists:payroll_periods,id',
            'payslip_details' => 'required|array',
            'payslip_details.*.employee_id' => 'required|exists:employees,id',
            'payslip_details.*.company_salary_type_id' => 'required|exists:company_salary_types,id',
            'payslip_details.*.name' => 'required',
            'payslip_details.*.amount' => 'required',
            'payslip_details.*.note' => 'required',
            'payslip_details.*.note' => 'nullable',
            'employee_ad_hoc_ids.*' => 'required|exists:employee_ad_hocs,id',
        ];
    }
}
