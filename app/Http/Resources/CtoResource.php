<?php

namespace App\Http\Resources;

use App\Models\Dtr;
use App\Http\Resources\Dtr2Resource;
use Illuminate\Http\Resources\Json\JsonResource;

class CtoResource extends JsonResource
{
    public function toArray($request)
    {
        $dtr = '';
        $start = date('M d, Y', strtotime($this->start));
        $end = date('M d, Y', strtotime($this->end));
        if($this->status->name == 'Approved' || $this->status->name == 'Confirmed'){
            $dtr = Dtr::where('user_id',$this->user->id)->whereBetween('date', [$this->start, $this->end])->get();
        }

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
            'total' => $this->total,
            'date' => ($start != $end) ? $start.' to '.$end : $start, 
            'purpose' => $this->purpose,
            'status' => $this->status,
            'profile' => $profile,
            'created_at' => date('M d, Y', strtotime($this->created_at)),
            'dtrs' => ($dtr) ? Dtr2Resource::collection($dtr) : ''
        ];
    }
}
