<?php

namespace App\Http\Resources;

use App\Http\Resources\RouteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'route_slip' => $this->route_slip,
            'subject' => $this->subject,
            'remarks' => $this->remarks,
            'keywords' => $this->keywords,
            'actions' => $this->actions,
            'action' => (in_array(86,json_decode($this->actions))) ? true : false,
            'document' => date("Y-m-d",strtotime($this->document)),
            'received' => date("Y-m-d",strtotime($this->received)),
            'document_at' => $this->document,
            'received_at' => $this->received,
            'is_completed' => $this->is_completed,
            'is_status' => $this->is_status,
            'sender' => $this->sender,
            'company' => $this->company,
            'type' => $this->type,
            'routings' => RouteResource::collection($this->routings),
            'attachments' => $this->attachments,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
