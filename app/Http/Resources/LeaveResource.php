<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   
        $weekendDays = 0;
        for ($i = strtotime($this->start); $i <= strtotime($this->end); $i = $i + (60 * 60 * 24)) {
            if (date("N", $i) > 5) $weekendDays = $weekendDays + 1;
        }

        $days = ((date_diff(date_create($this->start),  date_create($this->end))->format('%a')+1)-$weekendDays);

        $profile = [
            'name' => $this->user->profile->firstname.' '.$this->user->profile->middlename[0].'. '.$this->user->profile->lastname,
            'firstname' => $this->user->profile->firstname,
            'middlename' => $this->user->profile->middlename,
            'lastname' => $this->user->profile->lastname,
            'position' => $this->user->employee->position->name,
            'department' => $this->user->employee->department->others,
            'office' => $this->user->employee->office->name,
            'avatar' => $this->user->avatar
        ];
        return [
            'id' => $this->id,
            'days' => $days,
            'start' => date('M d, Y', strtotime($this->start)),
            'end' => date('M d, Y', strtotime($this->end)),
            'specify' => ($this->specify) ? $this->specify : 'n/a',
            'due' => ($this->due) ? $this->due : '' ,
            'is_requested' => $this->is_requested,
            'status' => $this->status,
            'detail' => $this->detail,
            'profile' => $profile,
            'type' => $this->type,
            'created_at' => date('M d, Y', strtotime($this->created_at))
        ];
    }
}
