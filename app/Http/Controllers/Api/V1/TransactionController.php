<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StockRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(StockRequest $request, $uuid) {
        $query = Transaction::query()
            ->where("stock", $request->stock)
            ->where("buyer", $uuid)
            ->Orwhere("seller", $uuid)
            ->where("stock", $request->stock)
            ->orderBy("updated_at", "desc")
            ->paginate(45);
        return TransactionResource::collection($query);
    }
}
