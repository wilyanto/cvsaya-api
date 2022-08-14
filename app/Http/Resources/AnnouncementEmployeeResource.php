<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementEmployeeResource extends JsonResource
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
            'announcement_id' => $this->announcement_id,
            'employee_id' => $this->employee_id,
            'note' => $this->note,
            'status' => $this->status,
            'replied_at' => $this->replied_at,
            'seen_at' => $this->seen_at,
            'announcement' => new AnnouncementResource($this->whenLoaded('announcement')),
            'employee' => new EmployeeResource($this->whenLoaded('employee'))
        ];
    }
}
