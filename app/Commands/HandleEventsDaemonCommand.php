<?php

declare(ticks=1);

namespace App\Commands;

use App\Application;

class HandleEventsDaemonCommand extends Command
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run(array $options = []): void
    {
        $this->initPcntl();

        $this->daemonRun($options);
    }

    public function daemonRun(array $options)
    {
        $lastData = $this->getLastData();

        $handleEventsCommand = new HandleEventsCommand($this->app);

        while (true) {
            if ($lastData === $this->getCurrentTime()) {
                sleep(10);

                continue;
            }

            $handleEventsCommand->run($options);

            $lastData = $this->getCurrentTime();

            sleep(10);
        }
    }
}