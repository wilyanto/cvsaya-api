<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'clock_in' => new AttendanceDetailResource($this->clockInAttendanceDetail),
            'clock_out' => new AttendanceDetailResource($this->clockOutAttendanceDetail),
            'start_break' => new AttendanceDetailResource($this->startBreakAttendanceDetail),
            'end_break' => new AttendanceDetailResource($this->endBreakAttendanceDetail),
            'employee' => new EmployeeResource($this->employee),
            'shift' => new ShiftResource($this->shift),
            'date' => $this->date,
        ];
    }
}
