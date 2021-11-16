<?php

declare(strict_types=1);

namespace App\src\Parser;

interface ParserInterface
{
    public function updateOhlcFromAllStocks(): array;

    public function updateParisFromAllStocks(): array;
}
