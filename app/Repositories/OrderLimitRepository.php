<?php

namespace App\Repositories;

use App\Models\OrderLimit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OrderLimitRepository
{
    // List order

    public static function getPlayerOrders($stock, $type, $owner): Collection
    {
        $builder = OrderLimit::query()->where("stock", $stock)->where("type", $type)->where("owner", $owner);
        return self::sortOrder($builder, $type)->get();
    }

    public static function getBestOrderWithout($stock, $type, $count, $except): Collection
    {
        $builder = OrderLimit::query()->where("stock", $stock)->where("type", $type)->whereNotIn("id", $except)->limit($count);
        return self::sortOrder($builder, $type)->get();
    }

    public static function getBestOrders($stock, $type, $count): Collection
    {
        $builder = OrderLimit::query()->where("stock", $stock)->where("type", $type)->limit($count);
        return self::sortOrder($builder, $type)->get();
    }

    private static function sortOrder($builder, $type): Builder
    {
        if ($type == "BUY")
            return $builder->orderBy("price", "desc");
        else
            return $builder->orderBy("price", "asc");
    }

    // Basics

    public static function create($array)
    {
        OrderLimit::query()->create($array);
    }

    public static function destroy($id)
    {
        OrderLimit::query()->findOrFail($id)->delete();
    }
}
