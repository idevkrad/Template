<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DtrResource extends JsonResource
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
            'username' => $this->username,
            'name' => $this->profile->firstname.' '.$this->profile->middlename[0].'. '.$this->profile->lastname,
            'avatar' => $this->avatar,
            'department' => $this->employee->department->name,
            'time' => date('M d, Y g:i:s a')
        ];
    }
}
