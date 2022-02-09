<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StockRequest;
use App\Http\Resources\DayStatResource;
use App\Repositories\DayStatRepository;
use App\Util\TransactionUtil;

class StockController extends Controller
{
    public function simpleStats(StockRequest $request): array
    {
        $price = TransactionUtil::getActualPrice($request->stock);
        return [
            "current_price" => $price
        ];
    }

    public function stats(StockRequest $request): array
    {
        $last_days = DayStatRepository::lastDays($request->stock, 8);
        return [
            "last_days" => DayStatResource::collection($last_days)
        ];
    }
}
