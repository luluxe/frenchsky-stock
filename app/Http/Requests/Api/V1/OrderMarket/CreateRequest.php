<?php

namespace App\Http\Requests\Api\V1\OrderMarket;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "server_id" => "required|string",
            "stock" => "required|string",
            "type" => "required|string|in:BUY,SELL",
            "player" => "required|string",
            "quantity" => "required|numeric",
            "money_spent" => "required|numeric"
        ];
    }
}
