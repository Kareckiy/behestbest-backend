<?php

declare(strict_types=1);

namespace App\src\TelegramPanel\Commands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommand extends SystemCommand
{
    protected $name = 'start';

    protected $description = 'Start command';

    protected $usage = '/start';

    protected $version = '1.2.0';

    protected $private_only = true;

    public function execute(): ServerResponse
    {
        return $this->replyToChat(
            'Hi'
        );
    }
}
