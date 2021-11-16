<?php

declare(strict_types=1);

namespace Tests\src\Stocks\Kraken;

use App\Models\Pair;
use App\src\Stocks\Kraken\KrakenClient;
use App\src\Stocks\Kraken\Requests\GetAssetPairsRequest;
use App\src\Stocks\Kraken\Requests\GetOhlcRequest;
use App\src\Stocks\Kraken\Requests\GetServerTimeRequest;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class KrakenClientTest extends TestCase
{
    public function testGetOhlcByPair(): void
    {
        $this->markTestSkipped('Внешний запрос');

        $pair = new Pair(
            [
                'altname' => 'XBTUSD'
            ]
        );

        $getOhlcRequest = new GetOhlcRequest($pair);

        $krakenClient = $this->getKrakenClient();

        $getOhlcResponse = $krakenClient->getOhlcByPair($getOhlcRequest);

        $this->assertNotEmpty($getOhlcResponse->getOhlcPerMoments());
    }

    public function testGetAssetPairs(): void
    {
        $this->markTestSkipped('Внешний запрос');

        $getAssetPairs = new GetAssetPairsRequest();

        $krakenClient = $this->getKrakenClient();

        $getAssetPairsResponse = $krakenClient->getAssetPairs($getAssetPairs);

        $this->assertNotEmpty($getAssetPairsResponse->getAssetPairs());
    }

    public function testGetServerTime(): void
    {
        $this->markTestSkipped('Внешний запрос');

        $getServerTimeRequest = new GetServerTimeRequest();

        $krakenClient = $this->getKrakenClient();

        $getServerTimeResponse = $krakenClient->getServerTime($getServerTimeRequest);

        $this->assertNotEmpty($getServerTimeResponse->getTimestamp());
    }

    private function getKrakenClient(): KrakenClient
    {
        return App::make(KrakenClient::class);
    }
}
