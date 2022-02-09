<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    public static function create($stock, $buyer, $seller, $price, $quantity)
    {
        Transaction::query()->create([
            "stock" => $stock,
            "buyer" => $buyer,
            "seller" => $seller,
            "price" => $price,
            "quantity" => $quantity,
        ]);
    }
}
