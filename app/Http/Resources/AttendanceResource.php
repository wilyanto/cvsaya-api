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
            'attendance_type' => $this->attendance_type,
            'attended_at' => $this->attended_at,
            'scheduled_at' => $this->scheduled_at,
            'attendance_qr_code_id' => $this->attendance_qr_code_id,
            'image' => $this->image,
            'image_url' => $this->getImageUrl(),
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'ip' => $this->ip,
            'verified_at' => $this->verified_at,
            'verified_by' => $this->verified_by,
            'shift_id' => $this->shift_id,
            'employee_id' => $this->employee_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'attendance_penalty' => $this->attendancePenalty,
            'outside_radius_note' => $this->outsideRadiusAttendance,
        ];
    }
}
