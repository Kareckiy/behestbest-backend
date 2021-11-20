<?php

namespace App\Console\Commands;

use App\src\Notifier\Notifier;
use App\src\Parser\Services\ActualizeService;
use Illuminate\Console\Command;

class CollectOhlc extends Command
{
    protected $signature = 'collect:ohlc';

    protected $description = 'Collecting ohlc';

    private ActualizeService $actualizeService;
    private Notifier $notifier;

    public function __construct(
        ActualizeService $actualizeService,
        Notifier $notifier
    )
    {
        parent::__construct();

        $this->actualizeService = $actualizeService;
        $this->notifier = $notifier;
    }

    public function handle()
    {
        $commandTitle = 'Ohlc collecting';

        $addOhlcResultDto = $this->actualizeService->addOhlc();

        $this->notifier->notifyCommandCollectingOhlcFinished(
            $commandTitle,
            $addOhlcResultDto
        );

        return Command::SUCCESS;
    }
}
