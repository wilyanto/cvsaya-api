<?php

namespace App\Http\Resources;

use App\Models\Attendance;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
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
            'name' => $this->name,
            'clock_in' => $this->clock_in,
            'clock_out' => $this->clock_out,
            'start_break' => $this->start_break,
            'end_break' => $this->end_break,
            'break_duration' => $this->break_duration,
            'company' => new CompanyResource($this->company),
            'attendance' => new AttendanceResource($this->whenLoaded('attendance')),
        ];
    }
}
