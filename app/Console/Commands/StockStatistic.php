<?php

namespace App\Console\Commands;

use App\Jobs\CreateStatistic;
use Illuminate\Console\Command;
use Illuminate\Queue\Jobs\Job;

class StockStatistic extends Command
{
    /**
     * /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:statistic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create statistics if not exists';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stocks = ["ecoins"];
        foreach ($stocks as $stock) {
            CreateStatistic::dispatch($stock)->onQueue($stock);
        }
        return 0;
    }
}
