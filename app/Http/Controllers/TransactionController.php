<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index($uuid) {
        $query = Transaction::query()
            ->where("buyer", $uuid)
            ->Orwhere("seller", $uuid)
            ->orderBy("updated_at", "desc")
            ->paginate(45);
        return TransactionResource::collection($query);
    }
}
