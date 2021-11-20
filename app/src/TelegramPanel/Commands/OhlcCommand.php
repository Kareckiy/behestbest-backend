<?php

declare(strict_types=1);

namespace App\src\TelegramPanel\Commands;

use App\Models\Ohlc;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Commands\UserCommand;

class OhlcCommand extends UserCommand
{
    protected $name = 'ohlc';

    protected $description = 'Get ohlc data';

    protected $usage = '/ohlc';

    protected $version = '1.2.0';

    public function execute(): ServerResponse
    {
        #$dataToCount = trim($this->getMessage()->getText(true));

        $countOhlc = Ohlc::count();
        $response = "Ohlc number: {$countOhlc}";

        return $this->replyToChat($response);
    }
}
