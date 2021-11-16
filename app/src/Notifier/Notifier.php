<?php

declare(strict_types=1);

namespace App\src\Notifier;

use App\src\Notifier\Clients\TelegramClient;
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
    ): void
    {
        $payload = [
            "Started {$commandTitle}",
        ];

        if ($elementsNumber) {
            $payload[] = "Elements number: {$elementsNumber}";
        }

        $this->telegramClient->sendMessage($payload);
    }

    public function notifyCommandCollectingOhlcFinished(
        string $commandTitle,
        int $elementsNumber,
        int $successElementsNumber,
        int $errorElementsNumber,
        DateInterval $duration
    ): void
    {
        $payload = [
            "Started {$commandTitle}",
            "El. number: {$elementsNumber}",
            "Success el. number: {$successElementsNumber}",
            "Error el. number: {$errorElementsNumber}",
            "Duration: {$duration->format('H:m')}",
        ];

        $this->telegramClient->sendMessage($payload);
    }

    public function notifyCommandCollectingPairsFinished(
        string $commandTitle,
        int $pairsCollected,
        int $pairsAdded,
        int $pairsDisabled,
        DateInterval $duration
    ): void
    {
        $payload = [
            "Started {$commandTitle}",
            "Collected pairs: {$pairsCollected}",
            "New pairs: {$pairsAdded}",
            "Disabled pairs: {$pairsDisabled}",
            "Duration: {$duration->format('H:m')}",
        ];

        $this->telegramClient->sendMessage($payload);
    }
}
