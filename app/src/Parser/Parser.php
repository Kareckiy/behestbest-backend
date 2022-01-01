<?php

declare(strict_types=1);

namespace App\src\Parser;

use App\Models\Pair;
use App\src\Stocks\Kraken\Dto\AssetPair;
use App\src\Stocks\Kraken\Exceptions\KrakenClientException;
use App\src\Stocks\Kraken\KrakenProvider;
use App\src\Stocks\Kraken\Responses\GetOhlcResponse;
use GuzzleHttp\Exception\GuzzleException;

class Parser
{
    private KrakenProvider $krakenProvider;

    private const USD = 'usd';

    public function __construct(KrakenProvider $krakenProvider)
    {
        $this->krakenProvider = $krakenProvider;
    }

    /**
     * @param  Pair  $pair
     * @return GetOhlcResponse
     * @throws GuzzleException
     * @throws KrakenClientException
     */
    public function getOhlcByPair(Pair $pair): GetOhlcResponse
    {
        $since = now()->subHour()->getTimestamp();

        return $this->krakenProvider->getOhlcByPair($pair, $since);
    }

    /**
     * @return AssetPair[]
     * @throws KrakenClientException
     * @throws GuzzleException
     */
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
