<?php

declare(strict_types=1);

namespace App\src\Notifier\Clients;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class TelegramClient
{
    private const MY_CHAT = 100;

    public function sendMessage(array $payload): void
    {
        try {
            Request::sendMessage(
                [
                    'chat_id' => self::MY_CHAT,
                    'parse_mode' => 'MARKDOWN',
                    'text' => implode('\n', $payload),
                ]
            );
        } catch (TelegramException $e) {
            // logger()->error();
        }
    }
}
