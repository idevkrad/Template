<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
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
            'code' => $this->code,
            'created' => $this->created_at,
            'profile' => $profile,
            'status' => $this->status,
            'type' => $this->type,
            'action' => $action,
            'bg' => $bg
        ];
        // return parent::toArray($request);
    }
}
