<?php

namespace App\Util;

use App\Models\DayStat;
use App\Repositories\DayStatRepository;
use App\Repositories\TransactionRepository;

class TransactionUtil
{
    public static function process($stock, $buyer, $seller, $price, $quantity)
    {
        // Day stats
        $day_stat = DayStatRepository::find($stock);

        // New day
        if ($day_stat == null) {
            $day_stat = self::getNewDay($stock);
        }

        // Update this day
        $day_stat->closing_price = $price;
        if ($day_stat->maximum_price < $price)
            $day_stat->maximum_price = $price;
        else if ($day_stat->minimum_price > $price)
            $day_stat->minimum_price = $price;
        $day_stat->volume += $price * $quantity;
        $day_stat->save();

        // Save to transaction table
        TransactionRepository::create($stock, $buyer, $seller, $price, $quantity);
    }

    public static function getNewDay($stock): DayStat
    {
        $day_stat = new DayStat();
        $day_stat->date = DayStatRepository::getDay();
        $day_stat->stock = $stock;
        $day_stat->opening_price = self::getActualPrice($stock);
        $day_stat->closing_price = $day_stat->opening_price;
        $day_stat->minimum_price = $day_stat->opening_price;
        $day_stat->maximum_price = $day_stat->opening_price;
        $day_stat->volume = 0;
        return $day_stat;
    }

    public static function getActualPrice($stock): float
    {
        $transaction = TransactionRepository::findLast($stock);
        if ($transaction == null)
            return 0;
        return $transaction->price;
    }
}
