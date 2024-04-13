<?php

namespace App\Commands;

use App\Application;

abstract class Command
{
    protected Application $app;

    const CACHE_PATH = __DIR__ . '/../../cache.txt';

    abstract function run(): void;

    protected function initPcntl(): void
    {
        $callback = function ($signal) {
            switch ($signal) {
                case SIGTERM:
                case SIGINT:
                case SIGHUP:
                    $lastData = $this->getCurrentTime();
                    $lastData[0] = $lastData[0] - 1;

                    file_put_contents(self::CACHE_PATH, json_encode($lastData));
                    exit;
            }
        };

        pcntl_signal(SIGTERM, $callback);
        pcntl_signal(SIGHUP, $callback);
        pcntl_signal(SIGINT, $callback);
    }

    protected function getCurrentTime(): array
    {
        return [
            date("i"),
            date("H"),
            date("d"),
            date("m"),
            date("w")
        ];
    }

    protected function getLastData(): array
    {
        $lastData = file_get_contents(self::CACHE_PATH);

        if ($lastData) {
            return json_decode($lastData);
        }

        return [];
    }
}