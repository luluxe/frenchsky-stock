<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DayStatResource extends JsonResource
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
            "date" => $this->date,
            "opening_price" => $this->opening_price,
            "closing_price" => $this->closing_price,
            "maximum_price" => $this->maximum_price,
            "minimum_price" => $this->minimum_price,
            "volume" => $this->volume,
        ];
    }
}
