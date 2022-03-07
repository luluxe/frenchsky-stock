<?php

namespace App\Jobs;

use App\Repositories\DayStatRepository;
use App\Repositories\HourStatRepository;
use App\Util\TransactionUtil;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateStatistic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $stock;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($stock)
    {
        $this->stock = $stock;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Day stats
        $day_stat = DayStatRepository::find($this->stock);
        if($day_stat == null) {
            $current_price = TransactionUtil::getActualPrice($this->stock);
            $day_stat = TransactionUtil::getNewDay($this->stock, $current_price);
            $day_stat->save();
        }

        // Hour stats
        $hour_stat = HourStatRepository::find($this->stock);
        if($hour_stat == null) {
            $current_price = TransactionUtil::getActualPrice($this->stock);
            $hour_stat = TransactionUtil::getNewHour($this->stock, $current_price);
            $hour_stat->save();
        }
    }
}
