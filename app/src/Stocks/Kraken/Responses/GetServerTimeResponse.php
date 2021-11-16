<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken\Responses;

class GetServerTimeResponse extends BaseResponse
{
    private int $timestamp;

    public function __construct(int $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public static function createFromJson(string $json): self
    {
        $serverTimeData = [];
        try {
            $jsonResponse = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $serverTimeData = $jsonResponse['result'];
        } catch (\Exception $e) {
            // logger()->warning();
        }

        return new self((int) $serverTimeData['unixtime']);
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}

