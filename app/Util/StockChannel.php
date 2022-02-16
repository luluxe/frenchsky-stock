<?php

namespace App\Util;

use Illuminate\Support\Facades\Redis;

class StockChannel
{
    public static function payMoney($server_id, $player, $amount)
    {
        self::send($server_id, "GIVE_MONEY", $player . self::DELIMITER . $amount);
    }

    public static function payStock($server_id, $player, $stock, $amount)
    {
        self::send($server_id, "GIVE_STOCK", $player . self::DELIMITER . $stock . self::DELIMITER . $amount);
    }

    public static function sendMessage($server_id, $player, $message, array $array)
    {
        self::send($server_id, "SEND_MESSAGE", $player . self::DELIMITER . $message . self::DELIMITER . implode(self::DELIMITER, $array));
    }

    // Channel

    const DELIMITER = ";;";

    /**
     * @param $server_id
     * @param $type
     * @param $parameters
     */
    private static function send($server_id, $type, $parameters)
    {
        Redis::publish("server:" . $server_id, "stock" . self::DELIMITER . $type . self::DELIMITER . $parameters);
    }
}
