<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\V1\StockRequest;
use App\Util\TransactionUtil;

class StockController extends Controller
{
    public function simpleStats(StockRequest $request) {
        $price = TransactionUtil::getActualPrice($request->stock);
        return [
            $price
        ];
    }

    public function stats(StockRequest $request) {
        return [2];
    }
}
