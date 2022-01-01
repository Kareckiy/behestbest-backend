<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken;

use App\Models\Pair;
use App\src\Stocks\Kraken\Requests\GetAssetPairsRequest;
use App\src\Stocks\Kraken\Requests\GetOhlcRequest;
use App\src\Stocks\Kraken\Responses\GetAssetPairsResponse;
use App\src\Stocks\Kraken\Responses\GetOhlcResponse;
use GuzzleHttp\Exception\GuzzleException;

class KrakenProvider
{
    private KrakenClient $krakenClient;

    public function __construct(KrakenClient $krakenClient)
    {
        $this->krakenClient = $krakenClient;
    }

    /**
     * @param  Pair  $pair
     * @param  int|null  $since
     * @param  int|null  $interval
     * @return GetOhlcResponse
     * @throws Exceptions\KrakenClientException
     * @throws GuzzleException
     */
    public function getOhlcByPair(Pair $pair, ?int $since = null, ?int $interval = null): GetOhlcResponse
    {
        $getOhlcRequest = new GetOhlcRequest(
            $pair,
            $interval,
            $since
        );

        return $this->krakenClient->getOhlcByPair($getOhlcRequest);
    }

    /**
     * @return GetAssetPairsResponse
     * @throws Exceptions\KrakenClientException
     * @throws GuzzleException
     */
    public function getAssetPairs(): GetAssetPairsResponse
    {
        $getAssetPairsRequest = new GetAssetPairsRequest();

        return $this->krakenClient->getAssetPairs($getAssetPairsRequest);
    }
}
