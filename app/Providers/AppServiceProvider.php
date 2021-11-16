<?php

namespace App\Providers;

use App\src\Notifier\Clients\ClientFactory;
use App\src\Parser\Parser;
use App\src\Parser\ParserInterface;
use Illuminate\Support\ServiceProvider;
use Longman\TelegramBot\Telegram;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->app->bind(
            ParserInterface::class,
            Parser::class
        );

        $this->app->singleton(Telegram::class, function ($app) {
            /** @var ClientFactory $clientFactory */
            $clientFactory = $app->make(ClientFactory::class);

            return $clientFactory->getTelegramClient();
        });
    }
}
