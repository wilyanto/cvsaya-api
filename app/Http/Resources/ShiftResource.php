<?php

namespace App\Http\Resources;

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
            'break_started_at' => $this->break_started_at,
            'break_ended_at' => $this->break_ended_at,
            'break_duration' => $this->break_duration,
            'company' => new CompanyResource($this->company),
        ];
    }
}
