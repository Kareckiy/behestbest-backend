<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken\Responses;

use App\src\Stocks\Kraken\Dto\OhlcPerMoment;

class GetOhlcResponse extends BaseResponse
{
    /** @var OhlcPerMoment[]  */
    private array $ohlcPerMoments;

    public function __construct(array $ohlcPerMoments)
    {
        $this->ohlcPerMoments = $ohlcPerMoments;
    }

    public static function createFromJson(string $json): self
    {
        $ohlcPerMoments = [];

        try {
            $jsonResponse = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $ohlcPerMoments = array_shift($jsonResponse['result']);
        } catch (\Exception $e) {
            // logger()->warning();
        }

        $ohlcPerMomentsDto = [];
        foreach ($ohlcPerMoments as $ohlcPerMoment) {
            $ohlcPerMomentsDto[] = new OhlcPerMoment(
                (int) $ohlcPerMoment[0],
                (string) $ohlcPerMoment[1],
                (string) $ohlcPerMoment[2],
                (string) $ohlcPerMoment[3],
                (string) $ohlcPerMoment[4],
                (string) $ohlcPerMoment[5],
                (string) $ohlcPerMoment[6],
                (int) $ohlcPerMoment[7],
            );
        }

        return new self($ohlcPerMomentsDto);
    }

    /** @return OhlcPerMoment[] */
    public function getOhlcPerMoments(): array
    {
        return $this->ohlcPerMoments;
    }
}
