<?php

declare(strict_types=1);

namespace App\src\Parser\Dto;

use DateInterval;

class AddOhlcResultDto
{
    private int $ohlcNumberCollected;
    private int $ohlcNumberAdded;
    private int $ohlcNumberOutdated;
    private DateInterval $duration;

    public function __construct(
        int $ohlcNumberCollected,
        int $ohlcNumberAdded,
        int $ohlcNumberOutdated,
        DateInterval $duration
    )
    {
        $this->ohlcNumberCollected = $ohlcNumberCollected;
        $this->ohlcNumberAdded = $ohlcNumberAdded;
        $this->ohlcNumberOutdated = $ohlcNumberOutdated;
        $this->duration = $duration;
    }

    public function getOhlcNumberCollected(): int
    {
        return $this->ohlcNumberCollected;
    }

    public function getOhlcNumberAdded(): int
    {
        return $this->ohlcNumberAdded;
    }

    public function getOhlcNumberOutdated(): int
    {
        return $this->ohlcNumberOutdated;
    }

    public function getDuration(): DateInterval
    {
        return $this->duration;
    }
}
