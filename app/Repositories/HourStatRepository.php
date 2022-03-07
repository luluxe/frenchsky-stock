<?php

namespace App\Repositories;

use App\Models\HourStat;
use Carbon\Carbon;

class HourStatRepository
{
    public static function find($stock): ?HourStat
    {
        $day_stat = HourStat::query()
            ->where("stock", $stock)
            ->where("date", DayStatRepository::getDay())
            ->where("hour", Carbon::now()->hour)
            ->limit(1)->get()->get(0);
        if ($day_stat != null)
            return $day_stat;
        return null;
    }
}
