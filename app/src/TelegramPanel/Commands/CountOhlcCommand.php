<?php

declare(strict_types=1);

namespace App\src\TelegramPanel\Commands;

use App\Models\Log;
use App\Models\Ohlc;
use Exception;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class CountOhlcCommand extends SystemCommand
{
    protected $name = 'count';

    protected $description = 'Count ohlc command';

    protected $usage = '/count';

    protected $version = '1.2.0';

    protected $private_only = true;

    public function execute(): ServerResponse
    {
        try {
            $countOhlc = Ohlc::count();

            return $this->replyToChat(
                'OHLC number: '.$countOhlc
            );
        } catch (Exception $e) {
            Log::create(
                [
                    'data' => 'RUN',
                    'created_at' => now()
                ]
            );
        }
    }
}
