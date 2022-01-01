<?php

declare(strict_types=1);

namespace Tests\Unit\src\Parser\Services;

use App\Models\Pair;
use App\src\Parser\Services\ActualizeService;
use App\src\Stocks\Kraken\Dto\AssetPair;
use App\src\Stocks\Kraken\Dto\OhlcPerMoment;
use App\src\Stocks\Kraken\KrakenProvider;
use App\src\Stocks\Kraken\Responses\GetAssetPairsResponse;
use App\src\Stocks\Kraken\Responses\GetOhlcResponse;
use App\Storages\PairsStorage;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ActualizeServiceTest extends TestCase
{
    public function testUpdatePairs(): void
    {
        $assetPair = new AssetPair(
            'USD/BTC',
            '',
            '',
            '',
            '',
            '',
        );

        $assetPairs = [$assetPair];
        $krakenProviderGetAssetPairsResponse = new GetAssetPairsResponse($assetPairs);

        $krakenProvider = $this->createMock(KrakenProvider::class);
        $krakenProvider
            ->method('getAssetPairs')
            ->willReturn($krakenProviderGetAssetPairsResponse);

        $this->app->singleton(KrakenProvider::class, function ($app) use ($krakenProvider) {
            return $krakenProvider;
        });

        /** @var ActualizeService $actualizeService */
        $actualizeService = App::make(ActualizeService::class);

        $updatePairsResponseDto = $actualizeService->updatePairs();

        $this->assertEquals(
            count($assetPairs),
            $updatePairsResponseDto->getCollectedPairsNumber()
        );

        $actualizeService->addOhlc();
    }

    public function testAddOhlc(): void
    {
        $activePair = new Pair(
            [
                'altname' => 'USD/BTC'
            ]
        );

        $pairsStorage = $this->createMock(PairsStorage::class);
        $pairsStorage
            ->method('getActivePairs')
            ->willReturn([$activePair]);

        $this->app->singleton(PairsStorage::class, function ($app) use ($pairsStorage) {
            return $pairsStorage;
        });

        $getOhlcPerMoment = new OhlcPerMoment(
            0,
            '100',
            '200',
            '0',
            '0',
            '0',
            '0',
            0,
        );
        $getOhlcPerMoments = [$getOhlcPerMoment, $getOhlcPerMoment];

        $getOhlcResponse = new GetOhlcResponse($getOhlcPerMoments);

        $krakenProvider = $this->createMock(KrakenProvider::class);
        $krakenProvider
            ->method('getOhlcByPair')
            ->willReturn($getOhlcResponse);

        $this->app->singleton(KrakenProvider::class, function ($app) use ($krakenProvider) {
            return $krakenProvider;
        });

        /** @var ActualizeService $actualizeService */
        $actualizeService = App::make(ActualizeService::class);

        $addOhlcResultDto = $actualizeService->addOhlc();

        $this->assertEquals(
            count($getOhlcPerMoments),
            $addOhlcResultDto->getOhlcNumberCollected()
        );
    }
}
