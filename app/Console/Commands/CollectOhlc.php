<?php

namespace App\Console\Commands;

use App\src\Parser\Services\ActualizeService;
use Illuminate\Console\Command;

class CollectOhlc extends Command
{
    protected $signature = 'collect:ohlc';

    protected $description = 'Collecting ohlc';

    private ActualizeService $actualizeService;

    public function __construct(ActualizeService $actualizeService)
    {
        parent::__construct();

        $this->actualizeService = $actualizeService;
    }

    public function handle()
    {
        $this->actualizeService->addOhlc();

        return Command::SUCCESS;
    }
}
