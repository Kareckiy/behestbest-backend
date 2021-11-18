<?php

declare(strict_types=1);

namespace App\src\Notifier\Clients;

use App\src\TelegramPanel\Commands\CountOhlcCommand;
use App\src\TelegramPanel\Commands\StartCommand;
use Longman\TelegramBot\Telegram;

class ClientFactory
{
    private array $commands = [
        StartCommand::class,
        CountOhlcCommand::class
    ];

    public function getTelegramClient(): Telegram
    {
        $telegram = new Telegram(
            config('app.telegram.api_key'),
            config('app.telegram.bot_username')
        );

        $telegram->addCommandClasses($this->commands);

        return $telegram;
    }
}
