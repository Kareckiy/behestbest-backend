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
        return $this->krakenProvider->getOhlcByPair($pair);
    }

    /** @return AssetPair[] */
    public function getUsdPairs(): array
    {
        $usdPairs = [];

        $assetPairs = $this->krakenProvider->getAssetPairs()->getAssetPairs();

        foreach ($assetPairs as $assetPair) {
            if (stripos($assetPair->getAltname(), self::USD)) {
                $usdPairs[] = $assetPair;
            }
        }

        return $usdPairs;
    }
}
