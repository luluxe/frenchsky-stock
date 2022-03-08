<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            "date" => Carbon::parse($this->updated_at)->toDateTimeString(),
            "stock" => $this->stock,
            "buyer" => $this->buyer,
            "seller" => $this->seller,
            "price" => $this->price,
            "quantity" => $this->quantity,
        ];
    }
}
