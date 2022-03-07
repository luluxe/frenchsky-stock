<?php

namespace App\Util;

use App\Models\DayStat;
use App\Models\HourStat;
use App\Repositories\DayStatRepository;
use App\Repositories\HourStatRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;

class TransactionUtil
{
    public static function process($stock, $buyer, $seller, $price, $quantity)
    {
        $current_price = self::getActualPrice($stock);

        // Day stats
        $day_stat = DayStatRepository::find($stock);
        $hour_stat = HourStatRepository::find($stock);

        // New day
        if ($day_stat == null)
            $day_stat = self::getNewDay($stock, $current_price);
        if ($hour_stat == null)
            $hour_stat = self::getNewHour($stock, $current_price);

        // Update this day
        $day_stat->closing_price = $price;
        if ($day_stat->maximum_price < $price)
            $day_stat->maximum_price = $price;
        else if ($day_stat->minimum_price > $price)
            $day_stat->minimum_price = $price;
        $day_stat->volume += $price * $quantity;
        $day_stat->save();

        // Update this hour
        $hour_stat->closing_price = $price;
        if ($hour_stat->maximum_price < $price)
            $hour_stat->maximum_price = $price;
        else if ($hour_stat->minimum_price > $price)
            $hour_stat->minimum_price = $price;
        $hour_stat->volume += $price * $quantity;
        $hour_stat->save();

        // Save to transaction table
        TransactionRepository::create($stock, $buyer, $seller, $price, $quantity);
    }

    public static function getNewDay($stock, $current_price): DayStat
    {
        $day_stat = new DayStat();
        $day_stat->date = DayStatRepository::getDay();
        $day_stat->stock = $stock;
        $day_stat->opening_price = $current_price;
        $day_stat->closing_price = $current_price;
        $day_stat->minimum_price = $current_price;
        $day_stat->maximum_price = $current_price;
        $day_stat->volume = 0;
        return $day_stat;
    }

    public static function getNewHour($stock, $current_price): HourStat
    {
        $day_stat = new HourStat();
        $day_stat->date = DayStatRepository::getDay();
        $day_stat->hour = Carbon::now()->hour;
        $day_stat->stock = $stock;
        $day_stat->opening_price = $current_price;
        $day_stat->closing_price = $current_price;
        $day_stat->minimum_price = $current_price;
        $day_stat->maximum_price = $current_price;
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
