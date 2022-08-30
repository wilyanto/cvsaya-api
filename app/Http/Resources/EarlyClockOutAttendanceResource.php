<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EarlyClockOutAttendanceResource extends JsonResource
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
            'attendance_detail_id' => $this->attendance_detail_id,
            'note' => $this->note,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'attendance_detail' => new AttendanceDetailResource($this->whenLoaded('attendanceDetail'))
        ];
    }
}
