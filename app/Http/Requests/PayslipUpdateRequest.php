<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayslipUpdateRequest extends FormRequest
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
            'employee_ad_hocs' => 'sometimes|array',
            'employee_ad_hocs.*.employee_id' => 'required|exists:employees,id',
            'employee_ad_hocs.*.company_salary_type_id' => 'required|exists:company_salary_types,id',
            'employee_ad_hocs.*.name' => 'required',
            'employee_ad_hocs.*.amount' => 'required',
            'employee_ad_hocs.*.date' => 'required',
            'employee_ad_hocs.*.note' => 'required',
        ];
    }
}
