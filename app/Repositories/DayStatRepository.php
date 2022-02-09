<?php

namespace App\Repositories;

use App\Models\DayStat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DayStatRepository
{
    public static function find($stock): ?DayStat
    {
        $day_stat = DayStat::query()->where("stock", $stock)->where("date", self::getDay())->limit(1)->get()->get(0);
        if ($day_stat != null)
            return $day_stat;
        return null;
    }

    public static function lastDays($stock, $count): Collection
    {
        return DayStat::query()->where("stock", $stock)->orderBy("date", "desc")->limit($count)->get();
    }

    public static function getDay(): string
    {
        return Carbon::now()->format("Y-m-d");
    }
}
