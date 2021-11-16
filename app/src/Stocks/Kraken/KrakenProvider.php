<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken;

use App\Models\Pair;
use App\src\Stocks\Kraken\Requests\GetAssetPairsRequest;
use App\src\Stocks\Kraken\Requests\GetOhlcRequest;
use App\src\Stocks\Kraken\Responses\GetAssetPairsResponse;
use App\src\Stocks\Kraken\Responses\GetOhlcResponse;

class KrakenProvider
{
    private KrakenClient $krakenClient;

    public function __construct(KrakenClient $krakenClient)
    {
        $this->krakenClient = $krakenClient;
    }

    public function getOhlcByPair(Pair $pair, ?int $since = null, ?int $interval = null): GetOhlcResponse
    {
        $getOhlcRequest = new GetOhlcRequest(
            $pair,
            $interval,
            $since
        );

        return $this->krakenClient->getOhlcByPair($getOhlcRequest);
    }

    public function getAssetPairs(): GetAssetPairsResponse
    {
        $getAssetPairsRequest = new GetAssetPairsRequest();

        return $this->krakenClient->getAssetPairs($getAssetPairsRequest);
    }
}
