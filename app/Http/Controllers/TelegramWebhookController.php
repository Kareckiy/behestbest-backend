<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

class TelegramWebhookController extends Controller
{
    private Telegram $telegram;

    public function __construct(Telegram $telegram)
    {
        $this->telegram = $telegram;
    }

    public function index(Request $request)
    {
        Log::create(['data' => file_get_contents("php://input"), 'time' => now()]);

        $this->telegram->addCommandsPath('/root/www/behestbest-backend/app/src/TelegramPanel/Commands');

        try {
            $this->telegram->handle();
            Log::create(['data' => 'success_hook', 'created_at' => now()]);
        } catch (\Exception $e) {
            Log::create(['data' => $e->getMessage(), 'created_at' => now()]);
        }

        return [];
    }

    public function set(Request $request)
    {
        $hookUrl = 'https://behest.best/api/telegram/webhook';

        try {
            $result = $this->telegram->setWebhook($hookUrl);
            if ($result->isOk()) {
                return $result->getDescription();
            }
        } catch (TelegramException $e) {
            Log::create(['data' => $e->getMessage(), 'created_at' => now()]);

            return $e->getMessage();
        }
    }
}