<?php

namespace App\Jobs;

use App\Repositories\OrderLimitRepository;
use App\Repositories\TransactionRepository;
use App\Util\StockChannel;
use App\Util\StockType;
use App\Util\TransactionUtil;
use Illuminate\Support\Facades\Log;

class BrokerJob
{
    private $price;
    private $quantity;
    private $money_spent;

    public function __construct($price, $quantity, $money_spent)
    {
        $this->price = $price;
        $this->quantity = $quantity;
        $this->money_spent = $money_spent;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getAveragePrice()
    {
        if ($this->quantity == 0)
            return 0;
        return $this->price / $this->quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getMoneySpent() {
        return $this->money_spent;
    }

    // Static

    /**
     * Work for OrderMarket and OrderLimit
     * OrderMarket have $limit to null
     * OrderLimit have money_spent to null
     *
     * @param $server_id
     * @param $stock
     * @param $type
     * @param $player
     * @param $quantity
     * @param $limit
     * @param $money_spent
     * @return BrokerJob
     */
    public static function process($server_id, $stock, $type, $player, $quantity, $limit, $money_spent)
    {
        $total_quantity = 0;
        $total_price = 0;

        // While we do not buy/sell the maximum
        while ($total_quantity != $quantity || $money_spent != 0) {
            // No other offers
            $order = OrderLimitRepository::getBestOrders($stock, StockType::inverseType($type), 1)->get(0);
            if ($order == null)
                break;

            // Limit reached
            if ($limit != null && self::isLimit($type, $limit, $order->price))
                break;

            $max_quantity = min($quantity - $total_quantity, $order->quantity);
            if ($type == StockType::BUY && isset($money_spent)) {
                $max_quantity = min($max_quantity, $money_spent / $order->price);
                $money_spent -= ($max_quantity * $order->price);
            }
            $total_quantity += $max_quantity;
            $total_price += $max_quantity * $order->price;


            // If offer was too expensive to buy any quantity
            if ($max_quantity == 0)
                break;

            // Create transactions
            if ($order->isSell()) {
                StockChannel::payMoney($server_id, $order->owner, $max_quantity * $order->price);
                TransactionUtil::process($stock, $player, $order->owner, $order->price, $max_quantity);
            } else {
                StockChannel::payStock($server_id, $order->owner, $stock,$max_quantity);
                TransactionUtil::process($stock, $order->owner, $player, $order->price, $max_quantity);
            }

            // Update order
            if ($order->quantity == $max_quantity) { // Offer completely buy
                $order->delete();
            } else { // Offer partially
                $order->quantity -= $max_quantity;
                $order->save();
            }
        }

        if ($total_quantity > 0) {
            if ($type == StockType::BUY) {
                StockChannel::payStock($server_id, $player, $stock, $total_quantity);
            } else {
                StockChannel::payMoney($server_id, $player, $total_price);
            }
        }
        return new BrokerJob($total_price, $total_quantity, $money_spent);
    }

    public static function isLimit($type, $limit, $price): bool
    {
        if ($type == StockType::BUY)
            return $price > $limit;
        else
            return $price < $limit;
    }
}
