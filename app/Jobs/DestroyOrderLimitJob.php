<?php

namespace App\Jobs;

use App\Repositories\OrderLimitRepository;
use App\Util\StockChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DestroyOrderLimitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type == "BUY") {
            // BUY
            StockChannel::payMoney("sky-spawn-1", $this->owner, $this->quantity * $this->price);
        } else {
            // SELL
            StockChannel::payStock("sky-spawn-1", $this->owner, $this->stock, $this->quantity);
        }
        OrderLimitRepository::destroy($this->id);
    }
}
