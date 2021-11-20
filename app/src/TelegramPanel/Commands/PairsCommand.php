<?php

declare(strict_types=1);

namespace App\src\TelegramPanel\Commands;

use App\Models\Pair;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Commands\UserCommand;

class PairsCommand extends UserCommand
{
    protected $name = 'pairs';

    protected $description = 'Get pairs data';

    protected $usage = '/pairs';

    protected $version = '1.2.0';

    public function execute(): ServerResponse
    {
        $countPairs = Pair::where('is_active', 1)->count();
        $response = "Active pairs number: {$countPairs}";

        return $this->replyToChat($response);

    }
}
