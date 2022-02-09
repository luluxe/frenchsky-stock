<?php

namespace App\Util;

use App\Repositories\OrderLimitRepository;

class SimulationMarketOffer
{
    private $price;
    private $amount;

    public function __construct($price, $amount)
    {
        $this->price = $price;
        $this->amount = $amount;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getAveragePrice() {
        if($this->amount == 0)
            return 0;
        return $this->price / $this->amount;
    }

    // Static

    public static function simulationOffer($stock, $type, $quantity): SimulationMarketOffer
    {
        $total_price = 0;
        $total_quantity = 0;
        $process_orders = array();

        // While we do not buy/sell the maximum
        while($total_quantity != $quantity) {
            // While we have another offer to use
            $order = OrderLimitRepository::getBestOrderWithout($stock, StockType::inverseType($type), 1, $process_orders)->get(0);
            if($order == null)
                break;

            array_push($process_orders, $order->id);

            $quantity_buy = min($quantity - $total_quantity, $order->quantity);
            $total_price += ($quantity_buy * $order->price);
            $total_quantity += $quantity_buy;
        }

        return new SimulationMarketOffer($total_price, $total_quantity);
    }
}
