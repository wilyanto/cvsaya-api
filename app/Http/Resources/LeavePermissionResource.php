<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeavePermissionResource extends JsonResource
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
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'reason' => $this->reason,
            'status' => $this->status,
            'answered_at' => $this->answered_at,
            'employee_id' => $this->employee_id,
            'occasion_id' => $this->occasion_id,
            'occasion' => new LeavePermissionOccasionResource($this->whenLoaded('occasion'))
        ];
    }
}