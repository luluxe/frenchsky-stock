<?php

namespace App\Jobs;

use App\Repositories\OrderLimitRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderLimitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function __construct($stock, $type, $owner, $price, $quantity)
    {
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
        $response = BrokerJob::process($this->stock, $this->type, $this->owner, $this->quantity, $this->price, null);
        if($response->getQuantity() == $this->quantity)
            return;

        OrderLimitRepository::create([
            "stock" => $this->stock,
            "type" => $this->type,
            "owner" => $this->owner,
            "price" => $this->price,
            "quantity" => $this->quantity - $response->getQuantity()
        ]);
    }
}
