<?php

namespace Tests\Feature;

use App\Models\Pair;
use App\src\Analyzer\Models\AnalyzeOhlcResult;
use App\src\Analyzer\Storages\AnalyzeOhlcStorage;
use App\src\Notifier\Dto\AnalyzeOhlcDto;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_example()
    {
        ini_set('memory_limit', '2G');

        /** @var AnalyzeOhlcStorage $analyzeOhlcStorage */
        $analyzeOhlcStorage = App::make(AnalyzeOhlcStorage::class);

        $pair = new Pair(
            [
                'altname' => '1INCHUSD'
            ]
        );
        $analyzeOhlcResult = $analyzeOhlcStorage->getNotAnalyzedOhlcByPair($pair);

        $this->assertNotEmpty($analyzeOhlcResult->toArray());
    }

    public function testIncorrect()
    {
        ini_set('memory_limit', '2G');

        /** @var AnalyzeOhlcStorage $analyzeOhlcStorage */
        $analyzeOhlcStorage = App::make(AnalyzeOhlcStorage::class);

        $pair = new Pair(
            [
                'altname' => '1INCHBTC'
            ]
        );
        $analyzeOhlcResult = $analyzeOhlcStorage->getNotAnalyzedOhlcByPair($pair);

        $this->assertEmpty($analyzeOhlcResult->toArray());
    }

    public function test_analyze_ohlc_result_model()
    {
        ini_set('memory_limit', '2G');

        $highPriceOfAnalyzedOhlc = AnalyzeOhlcResult::first()->ohlc->high_price;

        $this->assertNotEmpty($highPriceOfAnalyzedOhlc);
    }

    public function testAnalyzeOhlcStorage()
    {
        /** @var AnalyzeOhlcStorage $analyzeStorage */
        $analyzeStorage = App::make(AnalyzeOhlcStorage::class);
        $top = $analyzeStorage->getAnalyzedOhlcTopPerLastHoursByExcessPercent(3);

        dd($top->toArray());
    }

    public function testCreatingDto()
    {
        /** @var AnalyzeOhlcStorage $analyzeStorage */
        $analyzeStorage = App::make(AnalyzeOhlcStorage::class);

        $analyzeOhlcResults = $analyzeStorage->getAnalyzedOhlcTopPerLastHoursByExcessPercent(4);
        $analyzeOhlcResults->each(
            static function (AnalyzeOhlcResult $analyzeOhlcResult) use (&$analyzeOhlcDtos) {
                $analyzeOhlcDtos[] = new AnalyzeOhlcDto($analyzeOhlcResult->ohlc, $analyzeOhlcResult);
            }
        );

        dd($analyzeOhlcDtos);
    }

    public function testArray()
    {
        $arr1 = [1,2,3];
        $arr2 = [4,5,6];

        $arr3 = [];
        $arr3[] = $arr1;
        $arr3[] = $arr2;

        dd($arr3);
    }
}
