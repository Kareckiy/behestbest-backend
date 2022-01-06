<?php

declare(strict_types=1);

namespace Tests\Unit\src\Analyzer;

use App\Models\Ohlc;
use App\Models\Pair;
use App\src\Analyzer\Analyzer;
use App\src\Analyzer\Storages\AnalyzeOhlcStorage;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class AnalyzerTest extends TestCase
{
    // Целые числа, очевидная медиана
    public function testAnalyzeOhlcWithVolumesHigherThanWeekMedian(): void
    {
        $pair = new Pair(
            [
                'id' => 1,
                'altname' => 'XBTUSD'
            ]
        );

        $oldOhlcs =  new Collection([
            new Ohlc(['id' => 1, 'volume' => '10.000']),
            new Ohlc(['id' => 2, 'volume' => '20.000']),
            new Ohlc(['id' => 3, 'volume' => '30.000']),
        ]);

        $notProcessedOhlcs = new Collection([
            new Ohlc(['id' => 4, 'volume' => '100.000']),
            new Ohlc(['id' => 5, 'volume' => '200.000']),
            new Ohlc(['id' => 6, 'volume' => '300.000']),
        ]);

        $expectedExcessAbsolute = [
            80.0,
            180.0,
            280.0
        ];

        $expectedExcessPercent = [
            400.0,
            900.0,
            1400.0
        ];

        $analyzeOhlcStorage = $this->createMock(AnalyzeOhlcStorage::class);
        $analyzeOhlcStorage->method('getAnalyzedOhlcPerWeekByPair')->willReturn($oldOhlcs);
        $analyzeOhlcStorage->method('getNotAnalyzedOhlcByPair')->willReturn($notProcessedOhlcs);

        $analyzer = new Analyzer($analyzeOhlcStorage);
        $analyzeOhlcResults = $analyzer->analyzeOhlcWithVolumesHigherThanWeekMedian($pair);

        foreach ($analyzeOhlcResults as $i => $analyzeOhlcResult) {
            $this->assertEquals(
                $expectedExcessAbsolute[$i],
                $analyzeOhlcResult->getExcessAbsolute()
            );

            $this->assertEquals(
                $expectedExcessPercent[$i],
                $analyzeOhlcResult->getExcessPercent()
            );
        }
    }

    // Дробные числа, не очевидная медиана. Проверить, как считает медиану по string volume в бд
    public function testAnalyzeOhlcWithVolumesHigherThanMonthMedian(): void
    {
        $this->markTestSkipped();
    }

    public function testCountDifficultMedian(): void
    {
        $oldOhlcs = new Collection(
            [
                new Ohlc(['id' => 1, 'volume' => '4.00801596']),
                new Ohlc(['id' => 2, 'volume' => '14.05983767']),
                new Ohlc(['id' => 3, 'volume' => '137.95302000']),
            ]
        );

        $this->assertEquals(
            14.05983767,
            $oldOhlcs->median('volume')
        );
    }
}
