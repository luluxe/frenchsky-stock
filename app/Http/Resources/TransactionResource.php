<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "stock" => $this->stock,
            "buyer" => $this->buyer,
            "seller" => $this->seller,
            "price" => $this->price,
            "quantity" => $this->quantity,
        ];
    }
}
