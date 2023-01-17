<?php

namespace App\Http\Resources;

use App\Models\Dtr;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    public function toArray($request)
    {   
        switch($this->status->name){
            case 'Pending':
                $action = 'requested for a '.$this->type->name;
                $bg = 'bg-warning';
            break;
            case 'Validated':
                $action = 'requested for a '.$this->type->name;
                $bg = 'bg-primary';
            break;
            case 'Recommended':
                $action = $this->type->name.' is now recommended.';
                $bg = 'bg-info';
            break;
            case 'Approved':
                $action = $this->type->name.' request was approved';
                $bg = 'bg-success';
            break;
            case 'Disapproved':
                $action = $this->type->name.' request was disapproved';
                $bg = 'bg-danger';
            break;
        }

        $weekendDays = 0; $dtr ='';
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

        if($this->request_type == 69){
            $data = $this->leave;
        }else{
            $data = $this->cto;
            if($this->status->name == 'Approved' || $this->status->name == 'Confirmed'){
                $dtr = Dtr::where('user_id',$this->user->id)->whereBetween('date', [$this->start, $this->end])->get();
            }
        }
        $key = array_search(58, array_column(json_decode($this->statuses), 'status_id'));
        $start = date('M d, Y', strtotime($this->start));
        $end = date('M d, Y', strtotime($this->end));
        
        return [
            'id' => $this->id,
            'code' => $this->code,
            'days' => $days,
            'date' => ($start !=  $end) ? $start.' to '.$end : $start, 
            'start' => $start,
            'end' => $end,
            'due' => $this->due,
            'status' => $this->status,
            'type' => $this->type,
            'data' => $data,
            'dtr' => $dtr,
            'action' => $action,
            'bg' => $bg,
            'is_seened' => $this->is_seened,
            'profile' => $profile,
            'statuses' => $this->statuses,
            'status_at' => ($key) ? date('M d, Y h:i a', strtotime($this->statuses[$key]->created_at)) : 'n/a',
            'created_at' => date('M d, Y', strtotime($this->created_at))
        ];
       
        
    }
}
