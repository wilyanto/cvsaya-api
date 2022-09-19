<?php

namespace App\Http\Requests;

use App\Enums\EarlyClockOutAttendanceStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class EarlyClockOutAttendanceUpdateStatusRequest extends FormRequest
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
            'status' => ['required', new EnumRule(EarlyClockOutAttendanceStatusEnum::class)],
            'approved_by' => 'required|exists:employees,id'
        ];
    }
}
