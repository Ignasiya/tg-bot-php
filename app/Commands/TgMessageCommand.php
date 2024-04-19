<?php

namespace App\Commands;

use App\Application;
use App\Telegram\TelegramApiImp;

class TgMessageCommand extends Command
{
    protected Application $app;
    protected TelegramApiImp $telegramApi;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function setTelegramApi(TelegramApiImp $telegramApi): void
    {
        $this->telegramApi = $telegramApi;
    }

    function run(array $options = []): void
    {
        if (!isset($this->telegramApi)) {
            $this->telegramApi = new TelegramApiImp($this->app->env('TELEGRAM_TOKEN'));
        }
        echo json_encode($this->telegramApi->getMessage(0));
    }
}