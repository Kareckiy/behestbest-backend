<?php

declare(strict_types=1);

namespace App\src\Parser\Services;

use App\Models\Ohlc;
use App\Models\Pair;
use App\src\Parser\Dto\AddOhlcPerMomentsForPairsResultDto;
use App\src\Parser\Dto\AddOhlcResultDto;
use App\src\Parser\Dto\UpdatePairsResultDto;
use App\src\Parser\Parser;
use App\src\Stocks\Kraken\Dto\AssetPair;
use App\Storages\PairsStorage;
use Exception;

class ActualizeService
{
    private PairsStorage $pairsStorage;
    private Parser $parser;

    private const HALF_SECOND_IN_MS = 500000;

    public function __construct(
        PairsStorage $pairsStorage,
        Parser $parser
    )
    {
        $this->pairsStorage = $pairsStorage;
        $this->parser = $parser;
    }

    public function addOhlc(): AddOhlcResultDto
    {
        $pairs = $this->pairsStorage->getActivePairs();

        $collectedOhlcNumber = 0;
        $successAddedOhlcNumber = 0;
        $outdatedOhlcNumber = 0;
        $startTime = now();

        foreach ($pairs as $pair) {
            $ohlcByPair = $this->parser->getOhlcByPair($pair);

            $addOhlcPerMomentsForPair = $this->addOhlcPerMomentsForPair($ohlcByPair->getOhlcPerMoments(), $pair);
            $collectedOhlcNumber += $addOhlcPerMomentsForPair->getOhlcForPairNumber();
            $successAddedOhlcNumber += $addOhlcPerMomentsForPair->getAddedOhlcNumber();
            $outdatedOhlcNumber += $addOhlcPerMomentsForPair->getOutdatedOhlcNumber();

            usleep(self::HALF_SECOND_IN_MS);
        }

        $endTime = now();

        return new AddOhlcResultDto(
            $collectedOhlcNumber,
            $successAddedOhlcNumber,
            $outdatedOhlcNumber,
            $endTime->diff($startTime)
        );
    }

    private function addOhlcPerMomentsForPair(array $ohlcPerMoments, Pair $pair): AddOhlcPerMomentsForPairsResultDto
    {
        $allOhlcForPairNumber = count($ohlcPerMoments);
        $addedOhlcNumber = 0;
        $outdatedOhlcNumber = 0;

        $reversedOhlcPerMoments = array_reverse($ohlcPerMoments);

        foreach ($reversedOhlcPerMoments as $ohlcPerMoment) {
            try {
                Ohlc::createFromOhlcPerMoment($ohlcPerMoment, $pair);
                ++$addedOhlcNumber;
            } catch (Exception $e) {
                ++$outdatedOhlcNumber;
                // Поскольку в ohlc уникальный ключ altname, timestamp
            }
        }

        return new AddOhlcPerMomentsForPairsResultDto(
            $allOhlcForPairNumber,
            $addedOhlcNumber,
            $outdatedOhlcNumber
        );
    }

    public function updatePairs(): UpdatePairsResultDto
    {
        $startTime = now();

        $pulledPairs = $this->parser->getUsdPairs();
        $oldPairs = $this->pairsStorage->getActivePairs();

        $pairsToDeactivate = $this->filterDisabledPairsOfOldPairs($oldPairs, $pulledPairs);
        $pairsToAdd = $this->filterNewPairsOfPulledPairs($pulledPairs, $oldPairs);

        $this->addNewPairs($pairsToAdd);
        $this->disablePairs($pairsToDeactivate);

        $endTime = now();

        return new UpdatePairsResultDto(
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
