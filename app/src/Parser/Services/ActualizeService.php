<?php

declare(strict_types=1);

namespace App\src\Parser\Services;

use App\Models\Ohlc;
use App\Models\Pair;
use App\src\Notifier\Notifier;
use App\src\Parser\Parser;
use App\src\Stocks\Kraken\Dto\AssetPair;
use App\src\Stocks\Kraken\Dto\OhlcPerMoment;
use App\Storages\PairsStorage;
use Exception;

class ActualizeService
{
    private PairsStorage $pairsStorage;
    private Parser $parser;
    private Notifier $notifier;

    private const HALF_SECOND_IN_MS = 500000;

    public function __construct(
        PairsStorage $pairsStorage,
        Parser $parser,
        Notifier $notifier
    )
    {
        $this->pairsStorage = $pairsStorage;
        $this->parser = $parser;
        $this->notifier = $notifier;
    }

    public function addOhlc(): void
    {
        $commandTitle = 'Ohlc collecting';

        $pairs = $this->pairsStorage->getActivePairs();

        $this->notifier->notifyCommandStarted(
            $commandTitle,
            count($pairs)
        );

        $errorsCount = 0;
        $successAddedCount = 0;
        $startTime = now();

        foreach ($pairs as $pair) {
            $ohlcByPair = $this->parser->getOhlcByPair($pair);

            try {
                $this->addOhlcPerMomentsForPair($ohlcByPair->getOhlcPerMoments(), $pair);
                ++$successAddedCount;
            } catch (Exception $e) {
                ++$errorsCount;
                // logger()->error();
                // Поскольку в ohlc уникальный ключ altname, timestamp
            }

            usleep(self::HALF_SECOND_IN_MS);
            // логировать (слать в телегу) ошибки
        }

        $endTime = now();

        $this->notifier->notifyCommandCollectingOhlcFinished(
            $commandTitle,
            count($pairs),
            $successAddedCount,
            $errorsCount,
            $endTime->diff($startTime)
        );
    }

    /** @var OhlcPerMoment[] $ohlcPerMoments */
    private function addOhlcPerMomentsForPair(array $ohlcPerMoments, Pair $pair): void
    {
        $reversedOhlcPerMoments = array_reverse($ohlcPerMoments);

        foreach ($reversedOhlcPerMoments as $ohlcPerMoment) {
            Ohlc::createFromOhlcPerMoment($ohlcPerMoment, $pair);
        }
    }

    public function updatePairs(): void
    {
        $commandTitle = 'Collecting pairs';

        $this->notifier->notifyCommandStarted(
            $commandTitle,
        );

        $startTime = now();

        $pulledPairs = $this->parser->getUsdPairs();
        $oldPairs = $this->pairsStorage->getActivePairs();

        $pairsToDeactivate = $this->filterDisabledPairsOfOldPairs($oldPairs, $pulledPairs);
        $pairsToAdd = $this->filterNewPairsOfPulledPairs($pulledPairs, $oldPairs);

        $this->addNewPairs($pairsToAdd);
        $this->disablePairs($pairsToDeactivate);

        $endTime = now();

        $this->notifier->notifyCommandCollectingPairsFinished(
            $commandTitle,
            count($pulledPairs),
            count($pairsToAdd),
            count($pairsToDeactivate),
            $endTime->diff($startTime)
        );
    }

    /** @var AssetPair[] */
    private function addNewPairs(array $newPairs): void
    {
        foreach ($newPairs as $newPair) {
            Pair::createFromAssetPairDto($newPair);
        }
    }

    /** @var Pair[] */
    private function disablePairs(array $pairs): void
    {
        $pairsIds = array_map(static fn (Pair $pair): int => $pair->getId(), $pairs);

        $now = now();

        Pair::whereIn('id', $pairsIds)->update(
            [
                'is_active' => false,
                'updated_at' => $now,
            ]
        );
    }

    /**
     * @param AssetPair[] $pulledPairs
     * @param Pair[] $oldPairs
     * @return AssetPair[]
     */
    private function filterNewPairsOfPulledPairs(array $pulledPairs, array $oldPairs): array
    {
        $newPairs = [];

        foreach ($pulledPairs as $pulledPair) {
            $isNewPair = true;

            foreach ($oldPairs as $oldPair) {
                if ($pulledPair->getAltname() === $oldPair->getAltname()) {
                    $isNewPair = false;
                }
            }

            if ($isNewPair) {
                $newPairs[] = $pulledPair;
            }
        }

        return $newPairs;
    }

    /**
     * @param AssetPair[] $pulledPairs
     * @param Pair[] $oldPairs
     * @return Pair[]
     */
    private function filterDisabledPairsOfOldPairs(array $oldPairs, array $pulledPairs): array
    {
        $disabledPairs = [];

        foreach ($oldPairs as $oldPair) {
            $isDisabledPair = true;

            foreach ($pulledPairs as $pulledPair) {
                if ($oldPair->getAltname() === $pulledPair->getAltname()) {
                    $isDisabledPair = false;
                }
            }

            if ($isDisabledPair) {
                $disabledPairs[] = $oldPair;
            }
        }

        return $disabledPairs;
    }
}
