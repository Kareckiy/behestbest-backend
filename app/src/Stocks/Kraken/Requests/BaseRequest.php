<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken\Requests;

abstract class BaseRequest
{
    abstract public function toArray(): array;
}
