<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeePayslipAdHocUpdateRequest extends FormRequest
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
            'company_salary_type_id' => 'required|exists:company_salary_types,id',
            'name' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'note' => 'required'
        ];
    }
}
