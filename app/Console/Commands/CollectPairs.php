<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\src\Notifier\Notifier;
use App\src\Parser\Services\ActualizeService;
use Illuminate\Console\Command;

class CollectPairs extends Command
{
    protected $signature = 'collect:pairs';

    protected $description = 'Collecting trade pairs';

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
        $commandTitle = 'Collecting pairs';

        $updatePairsResultDto = $this->actualizeService->updatePairs();

        $this->notifier->notifyCommandCollectingPairsFinished(
            $commandTitle,
            $updatePairsResultDto
        );

        return Command::SUCCESS;
    }
}
