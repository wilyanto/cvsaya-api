<?php

namespace App\Http\Resources;

use App\Models\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class RecurringShiftResource extends JsonResource
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
            'day' => $this->day,
            'is_enabled' => $this->is_enabled,
            'shift_id' => $this->shift_id,
            'shift' => new ShiftResource($this->shift),
            'employee_id' => $this->employee_id,
            'employee' => new EmployeeResource($this->employee),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
