<?php

declare(strict_types=1);

namespace App\src\Notifier\Clients;

use Longman\TelegramBot\Telegram;

class ClientFactory
{
    public function getTelegramClient(): Telegram
    {
        $telegram = new Telegram(
            config('app.telegram.api_key'),
            config('app.telegram.bot_username')
        );

        #$telegram->addCommandsPath(config('app.telegram.commands_path'));

        return $telegram;
    }
}
