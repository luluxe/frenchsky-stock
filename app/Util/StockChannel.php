<?php

namespace App\Util;

use Illuminate\Support\Facades\Log;

class StockChannel
{
    public static function payMoney($player, $stock, $amount) {
        // TODO implement redis
        Log::debug("payMoney: " . $player . " : " . $amount);
    }

    public static function payStock($player, $stock, $amount) {
        // TODO implement redis
        Log::debug("payStock: " . $player . " : " . $amount);
    }

    public static function sendMessage($player, $message, array $array) {
        // TODO implement redis
        Log::debug("sendMessage: " . $player . " : " . $message . " " . implode(" ", $array));
    }
}
