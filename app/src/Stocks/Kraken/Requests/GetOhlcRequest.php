<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken\Requests;

use App\Models\Pair;

class GetOhlcRequest extends BaseRequest
{
    private Pair $pair;
    private ?int $interval;
    private ?int $since;

    public function __construct(
        Pair $pair,
        ?int $interval = null,
        ?int $since = null
    )
    {
        $this->pair = $pair;
        $this->interval = $interval;
        $this->since = $since;
    }

    public function getPair(): Pair
    {
        return $this->pair;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function getSince(): ?int
    {
        return $this->since;
    }

    public function toArray(): array
    {
        return [
            $this->pair,
            $this->interval,
            $this->since
        ];
    }
}
