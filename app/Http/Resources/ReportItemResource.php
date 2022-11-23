<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportItemResource extends JsonResource
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
            // 'in_acceptory' => $this->acceptor,
            // 'out_acceptory' => $this->out_stocks ? $this->out_stocks[0]["acceptor"] : "null",
            'in_quantity' => $this->quantity,
            'out_quantity' => $this->out_stocks ? $this->out_stocks[0]["quantity"] : "null",
            "open_quantity" => "0",
            'in_created_at' => $this->created_at,
            'out_created_at' => $this->out_stocks ? $this->out_stocks[0]["created_at"] : "null",
        ];
    }
}