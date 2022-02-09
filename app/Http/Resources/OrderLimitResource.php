<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderLimitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "stock" => $this->stock,
            "type" => $this->type,
            "owner" => $this->owner,
            "price" => $this->price,
            "quantity" => $this->quantity,
            "created_at" => $this->created_at,
        ];
    }
}
