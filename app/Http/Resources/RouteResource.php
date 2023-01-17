<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   
        $success = ['icon' => 'bxs-check-circle','color' => 'text-success'];
        $error = ['icon' => 'bxs-x-circle','color' => 'text-danger'];
        return [
            'id' => $this->id,
            'name' => $this->user->profile->firstname.' '.$this->user->profile->lastname,
            'avatar' => $this->user->avatar,
            'is_completed' => (!$this->is_completed) ? $error : $success,
            'completed_at' => ($this->completed == '') ? 'no data' : $this->completed,
            'is_seened' => (!$this->is_seen_to) ? $error : $success,
            'seened_at' => ($this->seened == '') ? 'no data' : $this->seened,
        ];
    }
}
