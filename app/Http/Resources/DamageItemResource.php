<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DamageItemResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->stocks ? $this->stocks->items->name : "null",
            'quantity' => $this->quantity,
            'stock_id' => $this->stocks ? $this->stocks->id : "null",
            'created_at' => $this->created_at,
        ];
    }
}