<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CRMCredentialBlastTypeUpdateResource extends FormRequest
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
            '*.credential_id' => 'required|exists:crm_credentials,id',
            '*.blast_type_id' => 'required|exists:blast_types,id',
            '*.priority' => 'required|numeric'
        ];
    }
}
