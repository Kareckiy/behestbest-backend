<?php

namespace App\Console\Commands;

use App\src\Parser\Services\ActualizeService;
use Illuminate\Console\Command;

class CollectPairs extends Command
{
    protected $signature = 'collect:pairs';

    protected $description = 'Collecting trade pairs';

    private ActualizeService $actualizeService;

    public function __construct(ActualizeService $actualizeService)
    {
        parent::__construct();

        $this->actualizeService = $actualizeService;
    }

    public function handle()
    {
        $this->actualizeService->updatePairs();

        return Command::SUCCESS;
    }
}
