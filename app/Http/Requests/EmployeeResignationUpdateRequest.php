<?php

namespace App\Http\Requests;

use App\Enums\EmployeeResignationConsiderationEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class EmployeeResignationUpdateRequest extends FormRequest
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
            'note' => 'required',
            'resignation_date' => 'required|date',
            'consideration' => ['required', new EnumRule(EmployeeResignationConsiderationEnum::class)]
        ];
    }
}
