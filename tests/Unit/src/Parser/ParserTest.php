<?php

declare(strict_types=1);

namespace Tests\Unit\src\Parser;

use App\Models\Pair;
use App\src\Parser\Parser;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ParserTest extends TestCase
{
    public function testGetOhlcByPairs(): void
    {
        $this->markTestSkipped('Внешний запрос');

        $pair = new Pair(
            [
                'altname' => 'XBTUSD'
            ]
        );

        /** @var Parser $parser */
        $parser = App::make(Parser::class);

        $ohlcByPair = $parser->getOhlcByPair($pair);

        $this->assertNotEmpty($ohlcByPair->getOhlcPerMoments());
    }

    public function testGetUsdPairs(): void
    {
        $this->markTestSkipped('Внешний запрос');

        /** @var Parser $parser */
        $parser = App::make(Parser::class);

        $usdPairs = $parser->getUsdPairs();
        $firstUsdPair = array_shift($usdPairs);

        $this->assertNotEmpty($firstUsdPair);
    }
}
