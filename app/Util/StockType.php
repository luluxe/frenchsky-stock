<?php

namespace App\Util;

class StockType
{
    public const BUY = "BUY";
    public const SELL = "SELL";

    public static function inverseType($type) {
        if($type == self::BUY)
            return self::SELL;
        return self::BUY;
    }
}
