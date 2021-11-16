<?php

declare(strict_types=1);

namespace App\Storages;

use App\Models\Pair;

class PairsStorage
{
    /** @return Pair[] */
    public function getActivePairs(): array
    {
        return Pair::where('is_active', true)->get()->all();
    }
}
