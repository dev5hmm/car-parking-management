<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            "id" => $this->id,
            "customer_name" => $this->customer->name ?? '',
            "customer_email" => $this->customer->email?? '',
            "author" => $this->whenLoaded('author')->name ?? '',
            "car_no" => $this->car_no,
            "note" => $this->note,
            "full_duration" => $this->duration.' '.$this->duration_type . ($this->duration > 1? 's' :''),
            "duration" => $this->duration,
            "duration_type" => $this->duration_type,
            "total" => $this->total,
            "has_paid" => $this->has_paid,
            "created_at" => date('Y-m-d H:i:s', strtotime($this->created_at)),

            "services" => ServiceResource::collection($this->whenLoaded('services')),
            "service_ids" => $this->whenLoaded('services')->pluck('id')->toArray(),
        ];
    }
}
