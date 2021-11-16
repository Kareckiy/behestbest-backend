<?php

declare(strict_types=1);

namespace Tests\src\Stocks\Kraken\Responses;

use App\src\Stocks\Kraken\Dto\OhlcPerMoment;
use App\src\Stocks\Kraken\Responses\GetOhlcResponse;
use PHPUnit\Framework\TestCase;

class GetOhlcResponseTest extends TestCase
{
    public function testCreateFromJson(): void
    {
        $jsonResponse = file_get_contents(dirname(__DIR__, 5) . '/Fixtures/Kraken/' . 'get_ohlc_response.json');

        $getOhlcResponse = GetOhlcResponse::createFromJson($jsonResponse);

        $this->assertEquals(
            $this->getFirstOhlcFromFixture($jsonResponse),
            $getOhlcResponse->getOhlcPerMoments()[0]
        );
    }

    private function getFirstOhlcFromFixture(string $jsonResponse): OhlcPerMoment
    {
        $jsonDecoded = json_decode($jsonResponse, true, 512, JSON_THROW_ON_ERROR);

        $ohlcPerFirstMoment = array_shift($jsonDecoded['result'])[0];

        return new OhlcPerMoment(
            (int) $ohlcPerFirstMoment[0],
            (float) $ohlcPerFirstMoment[1],
            (float) $ohlcPerFirstMoment[2],
            (float) $ohlcPerFirstMoment[3],
            (float) $ohlcPerFirstMoment[4],
            (float) $ohlcPerFirstMoment[5],
            (float) $ohlcPerFirstMoment[6],
            (int) $ohlcPerFirstMoment[7]
        );
    }
}
