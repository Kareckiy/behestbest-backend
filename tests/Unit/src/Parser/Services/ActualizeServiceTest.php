<?php

declare(strict_types=1);

namespace Tests\Unit\src\Parser\Services;

use App\src\Parser\Services\ActualizeService;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ActualizeServiceTest extends TestCase
{
    public function testUpdatePairs(): void
    {
        /** @var ActualizeService $actualizeService */
        $actualizeService = App::make(ActualizeService::class);

        #$actualizeService->updatePairs();

        $actualizeService->addOhlc();
    }
}
