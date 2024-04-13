<?php

namespace App\Commands;

use App\Application;
use App\Telegram\TelegramApiImp;

class TgMessageCommand extends Command
{
    private TelegramApiImp $tgApi;
    private array $messageHistory = [];
    private int $messageOffsets;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->tgApi = new TelegramApiImp($this->app->env('TELEGRAM_TOKEN'));
        $this->messageHistory = [];
        $this->loadMessageOffsets();
    }

    public function run(array $options = []): void
    {
        $this->initPcntl();

        $this->daemonRun($options);
    }

    private function daemonRun(array $options)
    {
        $lastData = $this->getLastData();

        while (true) {
            if ($lastData === $this->getCurrentTime()) {
                sleep(10);
                continue;
            }

            $messages = $this->tgApi->getMessage();

            $lastData = $this->getCurrentTime();
            sleep(10);
        }
    }

    private function saveMessage($message): void
    {
        $this->messageHistory[] = $message;
    }

    private function loadMessageOffsets()
    {

    }
}