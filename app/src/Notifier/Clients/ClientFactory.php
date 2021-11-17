<?php

declare(strict_types=1);

namespace App\src\Notifier\Clients;

use App\Http\Controllers\TelegramWebhookController;
use Longman\TelegramBot\Telegram;

class ClientFactory
{
    public function getTelegramClient(): Telegram
    {
        $telegram = new Telegram(
            config('app.telegram.api_key'),
            config('app.telegram.bot_username')
        );

        $telegram->addCommandsPath(TelegramWebhookController::COMMANDS_PATH);

        return $telegram;
    }
}
