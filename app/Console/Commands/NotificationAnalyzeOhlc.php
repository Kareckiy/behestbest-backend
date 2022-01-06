<?php

namespace App\Console\Commands;

use App\src\Analyzer\Models\AnalyzeOhlcResult;
use App\src\Analyzer\Storages\AnalyzeOhlcStorage;
use App\src\Notifier\Dto\AnalyzeOhlcDto;
use App\src\Notifier\Notifier;
use Illuminate\Console\Command;

class NotificationAnalyzeOhlc extends Command
{
    protected $signature = 'notification:analyze:ohlc';
    protected $description = 'Notification about ohlc higher than excess limit every 1 hour';

    private AnalyzeOhlcStorage $analyzeOhlcStorage;
    private Notifier $notifier;

    public function __construct(
        AnalyzeOhlcStorage $analyzeOhlcStorage,
        Notifier $notifier
    ) {
        $this->analyzeOhlcStorage = $analyzeOhlcStorage;
        $this->notifier = $notifier;

        parent::__construct();
    }

    public function handle()
    {
        $analyzeOhlcDtos = [];

        $analyzeOhlcResults = $this->analyzeOhlcStorage->getAnalyzedOhlcTopPerLastHoursByExcessPercent(1);
        $analyzeOhlcResults->each(
            static function (AnalyzeOhlcResult $analyzeOhlcResult) use (&$analyzeOhlcDtos) {
                $analyzeOhlcDtos[] = new AnalyzeOhlcDto($analyzeOhlcResult->ohlc, $analyzeOhlcResult);
            }
        );

        $this->notifier->notifyCommandAnalyzeOhlc($analyzeOhlcDtos);

        return Command::SUCCESS;
    }
}
