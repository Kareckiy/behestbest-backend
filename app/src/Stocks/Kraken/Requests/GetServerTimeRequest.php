<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken\Requests;

class GetServerTimeRequest extends BaseRequest
{
    public function toArray(): array
    {
        return [];
    }
}
