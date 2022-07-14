<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'clock_in' => 'required|date_format:H:i:s',
            'clock_out' => 'required|date_format:H:i:s',
            'start_break' => 'nullable|date_format:H:i:s',
            'end_break' => 'nullable|date_format:H:i:s',
            'break_duration' => 'nullable|integer',
            'company_id' => 'required|exists:App\Models\Company,id',
        ];
    }
}
