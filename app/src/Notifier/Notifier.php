<?php

declare(strict_types=1);

namespace App\src\Notifier;

use App\src\Notifier\Clients\TelegramClient;
use App\src\Notifier\Dto\AnalyzeOhlcDto;
use App\src\Parser\Dto\AddOhlcResultDto;
use App\src\Parser\Dto\UpdatePairsResultDto;
use DateInterval;

class Notifier
{
    private TelegramClient $telegramClient;

    public function __construct(TelegramClient $telegramClient)
    {
        $this->telegramClient = $telegramClient;
    }

    public function notifyCommandStarted(
        string $commandTitle,
        ?int $elementsNumber = null
    ): void {
        $payload = [
            "⌛️ Started {$commandTitle}",
        ];

        if ($elementsNumber !== null) {
            $payload[] = "Elements number: {$elementsNumber}";
        }

        $this->telegramClient->sendMessage($payload);
    }

    public function notifyCommandException(
        string $commandTitle,
        string $exceptionMessage
    ): void {
        $payload = [
            "⚠️ Exception during {$commandTitle}",
            "Error: $exceptionMessage"
        ];

        $this->telegramClient->sendMessage($payload);
    }

    public function notifyCommandAnalyzeOhlcFinished(
        string $commandTitle,
        array $analyzedOhlcResults,
        array $pairs,
        DateInterval $duration
    ): void {
        $payload = [
            "✅ Finished {$commandTitle}",
            "Analyzed ohlc: ".count($analyzedOhlcResults),
            "Analyzed pairs: ".count($pairs),
            "Duration: ".$duration->format("%H:%I:%S"),
        ];

        $this->telegramClient->sendMessage($payload);
    }

    public function notifyCommandCollectingOhlcFinished(
        string $commandTitle,
        AddOhlcResultDto $addOhlcResultDto
    ): void {
        $payload = [
            "✅ Finished {$commandTitle}",
            "Collected ohlc: {$addOhlcResultDto->getOhlcNumberCollected()}",
            "Added ohlc: {$addOhlcResultDto->getOhlcNumberAdded()}",
            "Outdated ohlc: {$addOhlcResultDto->getOhlcNumberOutdated()}",
            "Duration: ".$addOhlcResultDto->getDuration()->format("%H:%I:%S"),
        ];

        $this->telegramClient->sendMessage($payload);
    }

    public function notifyCommandCollectingPairsFinished(
        string $commandTitle,
        UpdatePairsResultDto $updatePairsResultDto
    ): void {
        $payload = [
            "✅ Finished {$commandTitle}",
            "Collected pairs: {$updatePairsResultDto->getCollectedPairsNumber()}",
            "New pairs: {$updatePairsResultDto->getNewPairs()}",
            "Disabled pairs: {$updatePairsResultDto->getDisabledPairs()}",
            "Duration: ".$updatePairsResultDto->getDuration()->format("%H:%I:%S"),
        ];

        $this->telegramClient->sendMessage($payload);
    }

    /**
     * @param AnalyzeOhlcDto[] $analyzeOhlcs
     */
    public function notifyCommandAnalyzeOhlc(array $analyzeOhlcs): void
    {
        $payload = [
            "📈 Analyze top",
        ];

        foreach ($analyzeOhlcs as $analyzeOhlc) {
            $payload[] = $analyzeOhlc->getStringDataForExcessPercent();
        }

        $this->telegramClient->sendMessage($payload);
    }
}
