<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CRMCredentialStoreRequest extends FormRequest
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
            'name' => 'required',
            'key' => 'required',
            'country_code' => 'required',
            'phone_number' => 'required',
            'is_active' => 'required',
            'expired_at' => 'required'
        ];
    }
}
