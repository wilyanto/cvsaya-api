<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeavePermissionStoreRequest extends FormRequest
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
            'company_id' => 'required|exists:companies,id',
            'started_at' => 'required|date_format:Y-m-d H:i:s',
            'ended_at' => 'date_format:Y-m-d H:i:s|after:started_at',
            'occasion_id' => 'required|exists:leave_permission_occasions,id',
            'document_ids' => 'required|array',
            'reason' => 'required',
        ];
    }
}
