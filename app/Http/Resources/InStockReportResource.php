<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InStockReportResource extends JsonResource
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
            'name' => $this->items ? $this->items->name : "null",
            // 'item_id' => $this->items ? $this->items->id : "null",
            // 'acceptor' => $this->acceptor,
            // 'sender' => $this->sender,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
        ];
    }
}
