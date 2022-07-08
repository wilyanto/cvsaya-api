<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeBankAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'taxpayer_identification_number' => $this->taxpayer_identification_number,
            'bank_name' => $this->bank_name,
            'bank_branch_name' => $this->bank_branch_name,
            'employee_id' => $this->employee_id,
            'employee' => new EmployeeResource($this->whenLoaded('employee'))
        ];
    }
}
