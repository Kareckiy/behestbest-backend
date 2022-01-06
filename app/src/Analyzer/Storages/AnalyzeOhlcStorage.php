<?php

declare(strict_types=1);

namespace App\src\Analyzer\Storages;

use App\Models\Ohlc;
use App\Models\Pair;
use App\src\Analyzer\Models\AnalyzeOhlcResult;
use Illuminate\Database\Eloquent\Collection;

class AnalyzeOhlcStorage
{
    public function getNotAnalyzedOhlcByPair(Pair $pair): Collection
    {
        return Ohlc::where(
            [
                ['altname', $pair->getAltname()]
            ]
        )->doesnthave('analyzeOhlcResults')->get();
    }

    public function getAnalyzedOhlcPerWeekByPair(Pair $pair): Collection
    {
        $now = now();
        $oneWeekAgo = now()->subWeek();

        return Ohlc::where(
            [
                ['altname', $pair->getAltname()],
                ['created_at', '>=', $oneWeekAgo],
                ['created_at', '<=', $now],
            ]
        )->has('analyzeOhlcResults')->get();
    }

    public function getAnalyzedOhlcPerMonthByPair(Pair $pair): Collection
    {
        $now = now();
        $oneMonthAgo = now()->subMonth();

        return Ohlc::where(
            [
                ['altname', $pair->getAltname()],
                ['created_at', '>=', $oneMonthAgo],
                ['created_at', '<=', $now],
            ]
        )->has('analyzeOhlcResults')->get();
    }

    public function getAnalyzedOhlcTopPerLastHoursByExcessPercent(
        int $hours,
        float $elementsInTopNumber = 20
    ): Collection {
        $now = now();
        $hoursAgo = now()->subMonths($hours);

        return AnalyzeOhlcResult::where(
            [
                ['excess_percent', '!=', NULL],
                ['created_at', '>=', $hoursAgo],
                ['created_at', '<=', $now],
            ]
        )
            ->orderBy('excess_percent', 'desc')
            ->limit($elementsInTopNumber)
            ->get();
    }
}
