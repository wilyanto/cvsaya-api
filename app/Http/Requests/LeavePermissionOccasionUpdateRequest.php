<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeavePermissionOccasionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'max_day' => 'numeric|min:0',
            'company_id' => 'required|exists:companies,id'
        ];
    }
}
