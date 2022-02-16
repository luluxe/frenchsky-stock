<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\OrderMarket\CreateRequest;
use App\Http\Requests\Api\V1\OrderMarket\InfoRequest;
use App\Jobs\OrderMarketJob;
use App\Util\SimulationMarketOffer;

class OrderMarketController extends Controller
{
    public function info(InfoRequest $request)
    {
        $simulation = SimulationMarketOffer::simulationOffer($request->stock, $request->type, $request->quantity);

        return [
            "amount" => $simulation->getAmount(),
            "price" => $simulation->getPrice(),
            "average_price" => $simulation->getAveragePrice()
        ];
    }

    public function create(CreateRequest $request)
    {
        $job = new OrderMarketJob($request->server_id, $request->stock, $request->type, $request->player, $request->quantity, $request->money_spent);
        $this->dispatch($job->onQueue($request->stock));
    }
}
