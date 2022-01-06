<?php

declare(strict_types=1);

namespace App\src\Analyzer;

use App\Models\Ohlc;
use App\Models\Pair;
use App\src\Analyzer\Models\AnalyzeOhlcResult;
use App\src\Analyzer\Models\AnalyzeOhlcTypesDict;
use App\src\Analyzer\Storages\AnalyzeOhlcStorage;
use Illuminate\Database\Eloquent\Collection;

class Analyzer
{
    private AnalyzeOhlcStorage $analyzeOhlcStorage;

    public function __construct(AnalyzeOhlcStorage $analyzeOhlcStorage)
    {
        $this->analyzeOhlcStorage = $analyzeOhlcStorage;
    }

    /**
     * @return AnalyzeOhlcResult[]
     */
    public function analyzeOhlcWithVolumesHigherThanWeekMedian(Pair $pair): array
    {
        $oldOhlc = $this->analyzeOhlcStorage->getAnalyzedOhlcPerWeekByPair($pair);
        $notProcessedOhlc = $this->analyzeOhlcStorage->getNotAnalyzedOhlcByPair($pair);

        return $this->analyzeOhlcWithFieldHigherMedian(
            $oldOhlc,
            $notProcessedOhlc,
            'volume',
            AnalyzeOhlcTypesDict::ANALYZE_TYPE_WEEK_MEDIAN_VALUE_ID
        );
    }

    /**
     * @return AnalyzeOhlcResult[]
     */
    public function analyzeOhlcWithVolumesHigherThanMonthMedian(Pair $pair): array
    {
        $oldOhlc = $this->analyzeOhlcStorage->getAnalyzedOhlcPerMonthByPair($pair);
        $notProcessedOhlc = $this->analyzeOhlcStorage->getNotAnalyzedOhlcByPair($pair);

        return $this->analyzeOhlcWithFieldHigherMedian(
            $oldOhlc,
            $notProcessedOhlc,
            'volume',
            AnalyzeOhlcTypesDict::ANALYZE_TYPE_MONTH_MEDIAN_VALUE_ID
        );
    }

    /**
     * @param  Collection  $oldOhlc
     * @param  Collection  $notProcessedOhlc
     * @param  string  $fieldToCompare
     * @param  int  $analyzeOhlcTypeId
     * @return AnalyzeOhlcResult[]
     */
    private function analyzeOhlcWithFieldHigherMedian(
        Collection $oldOhlc,
        Collection $notProcessedOhlc,
        string $fieldToCompare,
        int $analyzeOhlcTypeId
    ): array {
        $analyzeOhlcResults = [];

        $medianValue = $oldOhlc->median($fieldToCompare);
        $medianValue = $medianValue > 0 ? $medianValue : 1;

        $notProcessedOhlc->each(
            static function (Ohlc $ohlc) use (&$analyzeOhlcResults, $analyzeOhlcTypeId, $medianValue, $fieldToCompare) {
                $ohlcComparingValue = $ohlc->{$fieldToCompare};
                $excessAbsolute = $ohlcComparingValue - $medianValue;
                $excessPercent = $excessAbsolute > 0 ? $excessAbsolute / $medianValue * 100 : 0;

                $analyzeOhlcResults[] = new AnalyzeOhlcResult(
                    [
                        'ohlc_id' => $ohlc->getId(),
                        'analyze_ohlc_type_id' => $analyzeOhlcTypeId,
                        'excess_percent' => (float) number_format($excessPercent,2),
                        'excess_absolute' => (float) number_format($excessAbsolute,2),
                        'created_at' => now()
                    ]
                );
            }
        );

        return $analyzeOhlcResults;
    }
}

/**
 * 1. Протестировать Analyzer
 * 2. Убедиться, что парсер работает, как положено ++
 * 3. Убедиться, что парсер будет слать уведомления об ошибках -
 * 4. Подумать о логах, нужны ли они
 * 5. Перед релизом запустить миграцию, чтобы пометить все ohlc как проанализированные -
 * на их основании будут анализироваться следующие свечи
 */

/*
 * 1 вариант - промежуточное хранение уведомлений; их подготовка и отправление
 * 2 вариант - 2 отдельных джобы + команда
 * 2.1. Аналитика
 * 2.2. Каждые 4 часа группировать данные, подсчитывать и слать данные.
 * Формат: количество отклонений, среднее значение отклонения (для абс и процент)
 * 2.3. Команда с детализацией по паре: присылает все отклонения за выбранный период (по дефолту - сегодня + вчера)
 */
