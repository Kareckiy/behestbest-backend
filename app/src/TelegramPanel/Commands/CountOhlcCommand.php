<?php

declare(strict_types=1);

namespace App\src\TelegramPanel\Commands;

use App\Models\Ohlc;
use App\Models\Pair;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Commands\UserCommand;

class CountOhlcCommand extends UserCommand
{
    protected $name = 'count';

    protected $description = 'Count ohlc command';

    protected $usage = '/count <data>';

    protected $version = '1.2.0';

    public function execute(): ServerResponse
    {
        $dataToCount = trim($this->getMessage()->getText(true));

        $response = 'Не определена сущность для подсчета';

        switch ($dataToCount) {
            case 'ohlc':
                $countOhlc = Ohlc::count();
                $response = "Ohlc number: {$countOhlc}";
                break;
            case 'pairs':
                $countPairs = Pair::where('is_active', 1)->count();
                $response = "Active pairs number: {$countPairs}";
                break;
        }

        return $this->replyToChat($response);
    }
}
