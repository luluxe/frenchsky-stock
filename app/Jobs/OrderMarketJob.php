<?php

namespace App\Jobs;

use App\Util\StockChannel;
use App\Util\StockType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderMarketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $stock;
    private $type;
    private $player;
    private $quantity;
    private $money_spent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stock, $type, $player, $quantity, $money_spent)
    {
        $this->stock = $stock;
        $this->type = $type;
        $this->player = $player;
        $this->quantity = $quantity;
        $this->money_spent = $money_spent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = BrokerJob::process($this->stock, $this->type, $this->player, $this->quantity, null, $this->money_spent);
        $array = [$this->stock, $this->type, $response->getPrice(), $response->getQuantity(), $response->getAveragePrice()];
        StockChannel::sendMessage($this->player, "ORDER_MARKET_SUCCESS", $array);

        // Remaining money/stock

        if ($this->type == StockType::BUY && $response->getPrice() != $this->money_spent) {
            $amount = $this->money_spent - $response->getPrice();
            StockChannel::payMoney($this->player, $this->stock, $amount);
            StockChannel::sendMessage($this->player, "ORDER_MARKET_REMAINS", [ $this->stock, $this->type, $amount ]);
            return;
        }

        if ($this->type == StockType::SELL && $response->getQuantity() != $this->quantity) {
            $amount = $this->quantity - $response->getQuantity();
            StockChannel::payStock($this->player, $this->stock, $this->quantity - $response->getQuantity());
            StockChannel::sendMessage($this->player, "ORDER_MARKET_REMAINS", [ $this->stock, $this->type, $amount ]);
        }
    }
}
