<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\IndexRequest;
use App\Http\Requests\Api\V1\OrderLimit\CreateRequest;
use App\Http\Requests\Api\V1\OrderLimit\DestroyRequest;
use App\Http\Requests\Api\V1\OrderLimit\PlayerRequest;
use App\Http\Resources\OrderLimitResource;
use App\Jobs\DestroyOrderLimitJob;
use App\Jobs\OrderLimitJob;
use App\Repositories\OrderLimitRepository;
use App\Util\StockType;

class OrderLimitController extends Controller
{
    public function index(IndexRequest $request): array
    {
        // Return 22 Best BUY and 22 BEST SELL
        $best_buy_orders = OrderLimitRepository::getBestOrders($request->stock,StockType::BUY, 22);
        $best_sell_orders = OrderLimitRepository::getBestOrders($request->stock, StockType::SELL, 22);

        return [
            StockType::BUY => OrderLimitResource::collection($best_buy_orders),
            StockType::SELL => OrderLimitResource::collection($best_sell_orders),
        ];
    }

    public function player(PlayerRequest $request): array
    {
        // Return order of player
        $buy_orders = OrderLimitRepository::getPlayerOrders($request->stock,StockType::BUY, $request->owner);
        $sell_orders = OrderLimitRepository::getPlayerOrders($request->stock, StockType::SELL, $request->owner);

        return [
            StockType::BUY => OrderLimitResource::collection($buy_orders),
            StockType::SELL => OrderLimitResource::collection($sell_orders),
        ];
    }

    public function create(CreateRequest $request)
    {
        $job = new OrderLimitJob($request->stock, $request->type, $request->owner, $request->price, $request->quantity);
        $this->dispatch($job->onQueue($request->stock));
    }

    /**
     * Destroy the order limit
     *
     * @param DestroyRequest $request
     * @param $id
     * @return void
     */
    public function destroy(DestroyRequest $request, $id)
    {
        $job = new DestroyOrderLimitJob($id);
        $this->dispatch($job->onQueue($request->stock));
    }
}
