<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CvExperienceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'position' => 'required',
            'employment_type_id' => 'exists:App\Models\EmploymentType,id|required',
            'company_name' => 'required|string',
            'company_location' => 'nullable|string',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date',
            'jobdesc' => 'nullable|string',
            'resign_reason' => [
                'string',
                'min:20',
                'nullable',
                'required_with:ended_at',
            ],
            'reference' => 'nullable|string',
            'previous_salary' => 'required|integer',
            'payslip' => 'nullable', 'exists:App\Models\Document,id',
        ];
    }
}
