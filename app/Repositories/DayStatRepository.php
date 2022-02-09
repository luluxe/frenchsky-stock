<?php

namespace App\Repositories;

use App\Models\DayStat;
use Carbon\Carbon;

class DayStatRepository
{
    public static function find() : ?DayStat
    {
        $day_stat = DayStat::query()->where("date", self::getDay())->limit(1)->get()->get(0);
        if ($day_stat != null)
            return $day_stat;
        return null;
    }

    public static function getDay() : String {
        return Carbon::now()->format("Y-m-d");
    }
}
