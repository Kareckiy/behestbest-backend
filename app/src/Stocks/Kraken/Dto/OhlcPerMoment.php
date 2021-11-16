<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken\Dto;

class OhlcPerMoment
{
    private int $serverTime;

    private string $priceOpen;
    private string $priceHigh;
    private string $priceLow;
    private string $priceClose;

    private string $vwap;
    private string $volume;

    private int $count;

    public function __construct(
        int $serverTime,
        string $priceOpen,
        string $priceHigh,
        string $priceLow,
        string $priceClose,
        string $vwap,
        string $volume,
        int $count
    )
    {
        $this->serverTime = $serverTime;
        $this->priceOpen = $priceOpen;
        $this->priceHigh = $priceHigh;
        $this->priceLow = $priceLow;
        $this->priceClose = $priceClose;
        $this->vwap = $vwap;
        $this->volume = $volume;
        $this->count = $count;
    }

    public function getServerTime(): int
    {
        return $this->serverTime;
    }

    public function getPriceOpen(): string
    {
        return $this->priceOpen;
    }

    public function getPriceHigh(): string
    {
        return $this->priceHigh;
    }

    public function getPriceLow(): string
    {
        return $this->priceLow;
    }

    public function getPriceClose(): string
    {
        return $this->priceClose;
    }

    public function getVwap(): string
    {
        return $this->vwap;
    }

    public function getVolume(): string
    {
        return $this->volume;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
