<?php

declare(strict_types=1);

namespace App\src\Notifier\Dto;

use App\Models\Ohlc;
use App\src\Analyzer\Models\AnalyzeOhlcResult;

class AnalyzeOhlcDto
{
    private Ohlc $ohlc;
    private AnalyzeOhlcResult $analyzeOhlcResult;

    public function __construct(Ohlc $ohlc, AnalyzeOhlcResult $analyzeOhlcResult)
    {
        $this->ohlc = $ohlc;
        $this->analyzeOhlcResult = $analyzeOhlcResult;
    }

    public function getOhlc(): Ohlc
    {
        return $this->ohlc;
    }

    public function getAnalyzeOhlcResult(): AnalyzeOhlcResult
    {
        return $this->analyzeOhlcResult;
    }

    public function getStringDataForExcessPercent(): string
    {
        $createdAt = $this->ohlc->getCreatedAt()->format('H:i');
        $description = "[{$createdAt}] {$this->ohlc->getOpenPrice()}-{$this->ohlc->getHighPrice()}-{$this->ohlc->getClosePrice()}";

        return "*{$this->ohlc->getAltname()}*: {$this->analyzeOhlcResult->getExcessPercent()}% ({$this->analyzeOhlcResult->getExcessAbsolute()}). \n{$description}";
    }
}
