<?php

namespace App\Console\Commands;

use App\src\Analyzer\Analyzer;
use App\src\Analyzer\Models\AnalyzeOhlcResult;
use App\src\Notifier\Notifier;
use App\Storages\PairsStorage;
use Illuminate\Console\Command;

class AnalyzeOhlc extends Command
{
    protected $signature = 'analyze:ohlc';
    protected $description = 'Analyze ohlc';

    private PairsStorage $pairsStorage;
    private Analyzer $analyzer;
    private Notifier $notifier;

    public function __construct(
        PairsStorage $pairsStorage,
        Analyzer $analyzer,
        Notifier $notifier
    ) {
        parent::__construct();

        $this->pairsStorage = $pairsStorage;
        $this->analyzer = $analyzer;
        $this->notifier = $notifier;
    }

    public function handle(): int
    {
        $pairs = $this->pairsStorage->getActivePairs();

        $this->analyzeWithWeekMedian($pairs);

        return Command::SUCCESS;
    }

    private function analyzeWithWeekMedian(array $pairs): void
    {
        $analyzeOhlcResults = [];
        $startTime = now();

        foreach ($pairs as $pair) {
            $analyzeResults = $this->analyzer->analyzeOhlcWithVolumesHigherThanWeekMedian($pair);

            array_map(static function (AnalyzeOhlcResult $analyzeOhlcResult) use (&$analyzeOhlcResults) {
                $analyzeOhlcResult->save();
                $analyzeOhlcResults[] = $analyzeOhlcResult;
            }, $analyzeResults);
        }

        $this->notifier->notifyCommandAnalyzeOhlcFinished(
            '🔭 Analyzing ohlc with week median',
            $analyzeOhlcResults,
            $pairs,
            now()->diff($startTime)
        );
    }

    private function analyzeWithMonthMedian(array $pairs): void
    {
        $analyzeOhlcResults = [];
        $startTime = now();

        foreach ($pairs as $pair) {
            $analyzeResults = $this->analyzer->analyzeOhlcWithVolumesHigherThanMonthMedian($pair);
            array_map(static fn (AnalyzeOhlcResult $analyzeOhlcResult): bool => $analyzeOhlcResult->save(), $analyzeResults);

            $analyzeOhlcResults[] = $analyzeResults;
        }

        $this->notifier->notifyCommandAnalyzeOhlcFinished(
            'Analyzing ohlc with month median',
            $analyzeOhlcResults,
            $pairs,
            now()->diff($startTime)
        );
    }
}
