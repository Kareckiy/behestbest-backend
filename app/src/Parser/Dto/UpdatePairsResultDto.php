<?php

declare(strict_types=1);

namespace App\src\Parser\Dto;

use DateInterval;

class UpdatePairsResultDto
{
    private int $collectedPairsNumber;
    private int $newPairs;
    private int $disabledPairs;
    private DateInterval $duration;

    public function __construct(int $collectedPairsNumber, int $newPairs, int $disabledPairs, \DateInterval $duration)
    {
        $this->collectedPairsNumber = $collectedPairsNumber;
        $this->newPairs = $newPairs;
        $this->disabledPairs = $disabledPairs;
        $this->duration = $duration;
    }

    public function getCollectedPairsNumber(): int
    {
        return $this->collectedPairsNumber;
    }

    public function getNewPairs(): int
    {
        return $this->newPairs;
    }

    public function getDisabledPairs(): int
    {
        return $this->disabledPairs;
    }

    public function getDuration(): DateInterval
    {
        return $this->duration;
    }
}
