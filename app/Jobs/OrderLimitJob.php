<?php

namespace App\Jobs;

use App\Repositories\OrderLimitRepository;
use App\Util\StockChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderLimitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $server_id;
    private $stock;
    private $type;
    private $owner;
    private $price;
    private $quantity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($server_id, $stock, $type, $owner, $price, $quantity)
    {
        $this->server_id = $server_id;
        $this->stock = $stock;
        $this->type = $type;
        $this->owner = $owner;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Check max offer
        $orders = OrderLimitRepository::getPlayerOrders($this->stock, $this->type, $this->owner);
        if(count($orders) == 9) {
            StockChannel::sendMessage($this->server_id, $this->owner, "ORDER_LIMIT_MAX", []);
            if($this->type == "BUY")
                StockChannel::payMoney($this->server_id, $this->owner, $this->quantity * $this->price);
            else
                StockChannel::payStock($this->server_id, $this->owner, $this->stock, $this->quantity);
            return;
        }

        $response = BrokerJob::process($this->server_id, $this->stock, $this->type, $this->owner, $this->quantity, $this->price, null);
        if ($response->getQuantity() == $this->quantity) {
            StockChannel::sendMessage($this->server_id, $this->owner, "ORDER_LIMIT_SELL", []);
            return;
        }

        StockChannel::sendMessage($this->server_id, $this->owner, "ORDER_LIMIT_CREATE", []);
        OrderLimitRepository::create([
            "stock" => $this->stock,
            "type" => $this->type,
            "owner" => $this->owner,
            "price" => $this->price,
            "quantity" => $this->quantity - $response->getQuantity()
        ]);
    }
}
