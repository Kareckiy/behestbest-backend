<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken;

use App\src\Stocks\Kraken\Exceptions\KrakenClientException;
use App\src\Stocks\Kraken\Requests\BaseRequest;
use App\src\Stocks\Kraken\Requests\GetAssetPairsRequest;
use App\src\Stocks\Kraken\Requests\GetOhlcRequest;
use App\src\Stocks\Kraken\Requests\GetServerTimeRequest;
use App\src\Stocks\Kraken\Responses\GetAssetPairsResponse;
use App\src\Stocks\Kraken\Responses\GetOhlcResponse;
use App\src\Stocks\Kraken\Responses\GetServerTimeResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class KrakenClient
{
    private Client $client;

    private string $url = 'https://api.kraken.com';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param  GetOhlcRequest  $getOhlcRequest
     * @return GetOhlcResponse
     * @throws KrakenClientException
     * @throws GuzzleException
     */
    public function getOhlcByPair(GetOhlcRequest $getOhlcRequest)
    {
        $urlPath = '/0/public/OHLC';

        $response = $this->client->get(
            $this->url . $urlPath,
            [
                'headers' => [
                    'API-Key' => config('stocks.kraken.apiKey'),
                    'API-Sign' => $this->makeSignature($getOhlcRequest, $urlPath)
                ],
                'query' => [
                    'pair' => $getOhlcRequest->getPair()->getAltname(),
                    'interval' => $getOhlcRequest->getInterval(),
                    'since' => $getOhlcRequest->getSince(),
                ]
            ]
        );

        $responseJson = $response->getBody()->getContents();

        $responseDecoded = json_decode($responseJson, true);
        if (! empty($responseDecoded['error'])) {
            throw new KrakenClientException(implode(', ', $responseDecoded['error']));
        }

        return GetOhlcResponse::createFromJson($responseJson);
    }

    /**
     * @param  GetAssetPairsRequest  $getAssetPairsRequest
     * @return GetAssetPairsResponse
     * @throws KrakenClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAssetPairs(GetAssetPairsRequest $getAssetPairsRequest): GetAssetPairsResponse
    {
        $urlPath = '/0/public/AssetPairs';

        $response = $this->client->get(
            $this->url . $urlPath,
            [
                'headers' => [
                    'API-Key' => config('stocks.kraken.apiKey'),
                    'API-Sign' => $this->makeSignature($getAssetPairsRequest, $urlPath)
                ],
            ]
        );

        $responseJson = $response->getBody()->getContents();

        $responseDecoded = json_decode($responseJson, true);
        if (! empty($responseDecoded['error'])) {
            throw new KrakenClientException(implode(', ', $responseDecoded['error']));
        }

        return GetAssetPairsResponse::createFromJson($responseJson);
    }

    public function getServerTime(GetServerTimeRequest $getServerTime): GetServerTimeResponse
    {
        $urlPath = '/0/public/Time';

        $response = $this->client->get(
            $this->url . $urlPath,
            [
                'headers' => [
                    'API-Key' => config('stocks.kraken.apiKey'),
                    'API-Sign' => $this->makeSignature($getServerTime, $urlPath)
                ],
            ]
        );

        $responseJson = $response->getBody()->getContents();

        $responseDecoded = json_decode($responseJson, true);
        if (! empty($responseDecoded['error'])) {
            throw new KrakenClientException(implode(', ', $responseDecoded['error']));
        }

        return GetServerTimeResponse::createFromJson($responseJson);
    }

    private function makeSignature(BaseRequest $baseRequest, string $urlPath = ''): string
    {
        $query = http_build_query($baseRequest->toArray());

        $decodePrivateKey = base64_decode(config('stocks.kraken.apiSecret'));
        $currentTime = time();

        $apiSHA256 = hash('sha256', $currentTime.$query);
        $apiHMAC = hash_hmac('sha512', $urlPath.$apiSHA256, $decodePrivateKey);

        return base64_encode($apiHMAC);
    }
}
