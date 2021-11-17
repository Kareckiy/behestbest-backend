<?php

declare(strict_types=1);

namespace App\src\Notifier\Clients;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class TelegramClient
{
    private const MY_CHAT = 548801634;

    private Telegram $telegram;

    public function __construct(Telegram $telegram)
    {
        $this->telegram = $telegram;
    }

    public function sendMessage(array $payload): void
    {
        try {
            Request::sendMessage(
                [
                    'chat_id' => self::MY_CHAT,
                    'parse_mode' => 'MARKDOWN',
                    'text' => implode("\n", $payload),
                ]
            );
        } catch (TelegramException $e) {
            // logger()->error();
        }
    }
}
