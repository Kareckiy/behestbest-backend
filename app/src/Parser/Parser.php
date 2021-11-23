<?php

declare(strict_types=1);

namespace App\src\Parser;

use App\Models\Pair;
use App\src\Stocks\Kraken\Dto\AssetPair;
use App\src\Stocks\Kraken\KrakenProvider;
use App\src\Stocks\Kraken\Responses\GetOhlcResponse;

class Parser
{
    private KrakenProvider $krakenProvider;

    private const USD = 'usd';

    public function __construct(KrakenProvider $krakenProvider)
    {
        $this->krakenProvider = $krakenProvider;
    }

    /**
     * @var Pair $pair
     * @return GetOhlcResponse
     */
    public function getOhlcByPair(Pair $pair): GetOhlcResponse
    {
        $since = now()->subHour()->getTimestamp();

        return $this->krakenProvider->getOhlcByPair($pair, $since);
    }

    /** @return AssetPair[] */
    public function getUsdPairs(): array
    {
        $usdPairs = [];

        $assetPairsResponse = $this->krakenProvider->getAssetPairs();
        $assetPairs = $assetPairsResponse->getAssetPairs();

        foreach ($assetPairs as $assetPair) {
            $strposUsd = stripos($assetPair->getAltname(), self::USD);
            if (is_int($strposUsd) && $strposUsd >= 0) {
                $usdPairs[] = $assetPair;
            }
        }

        return $usdPairs;
    }
}
