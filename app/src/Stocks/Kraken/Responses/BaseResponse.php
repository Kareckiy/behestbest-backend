<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken\Responses;

abstract class BaseResponse
{
    abstract public static function createFromJson(string $json): self;
}
