<?php

declare(strict_types=1);

namespace App\src\Parser\Dto;

class AddOhlcPerMomentsForPairsResultDto
{
    private int $ohlcForPairNumber;
    private int $addedOhlcNumber;
    private int $outdatedOhlcNumber;

    public function __construct(
        int $ohlcForPairNumber,
        int $addedOhlcNumber,
        int $outdatedOhlcNumber
    )
    {
        $this->ohlcForPairNumber = $ohlcForPairNumber;
        $this->addedOhlcNumber = $addedOhlcNumber;
        $this->outdatedOhlcNumber = $outdatedOhlcNumber;
    }

    public function getOhlcForPairNumber(): int
    {
        return $this->ohlcForPairNumber;
    }

    public function getAddedOhlcNumber(): int
    {
        return $this->addedOhlcNumber;
    }

    public function getOutdatedOhlcNumber(): int
    {
        return $this->outdatedOhlcNumber;
    }
}
