<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->user_id,
            'office' => $this->office,
            'department' => $this->department,
            'status' => $this->status,
            'position' => $this->position,
            'religion' => $this->religion,
            'level' => $this->level,
            'bloodtype' => $this->bloodtype,
            'email' => $this->user->email,
            'username' => $this->user->username,
            'role' => $this->user->role,
            'is_active' => $this->user->is_active,
            'avatar' => $this->user->avatar,
            'profile_id' => $this->user->profile->id,
            'firstname' => $this->user->profile->firstname,
            'middlename' => $this->user->profile->middlename,
            'lastname' => $this->user->profile->lastname,
            'mobile' => $this->user->profile->mobile,
            'birthday' => $this->user->profile->birthday,
            'gender' => $this->user->profile->gender,
            'groups' => GroupResource::collection($this->user->groups),
            'roles' => RoleResource::collection($this->user->roles),
            'created_at' => $this->user->created_at,
            'updated_at' => $this->user->updated_at
        ];
    }
}
