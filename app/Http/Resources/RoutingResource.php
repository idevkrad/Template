<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoutingResource extends JsonResource
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
        $warning = ['icon' => 'bxs-info-circle','color' => 'text-warning'];
        $error = ['icon' => 'bxs-x-circle','color' => 'text-danger'];

        return [
            'id' => $this->document->id,
            'route_slip' => $this->document->route_slip,
            'subject' => $this->document->subject,
            'remarks' => $this->document->remarks,
            'keywords' => $this->document->keywords,
            'actions' => $this->document->actions,
            'action' => (in_array(86,json_decode($this->document->actions))) ? true : false,
            'document' => date("Y-m-d",strtotime($this->document->document)),
            'received' => date("Y-m-d",strtotime($this->document->received)),
            'document_at' => $this->document->document,
            'received_at' => $this->document->received,
            'is_completed' => $this->document->is_completed,
            'is_status' => $this->document->is_status,
            'sender' => $this->document->sender,
            'company' => $this->document->company,
            'type' => $this->document->type,
            'routings' => RouteResource::collection($this->document->routings),
            'attachments' => $this->document->attachments,
            'created_at' => $this->document->created_at,
            'updated_at' => $this->document->updated_at,
            'is_completed' => (!$this->is_completed) ? ($this->is_seen_to) ? $warning : $error : $success,
            'completed_at' => ($this->completed == '') ? 'no data' : $this->completed,
            'is_seened' => (!$this->is_seen_to) ? $error : $success,
            'completed' => $this->completed
        ];
    }
}
