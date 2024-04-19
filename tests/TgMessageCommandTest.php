<?php

use App\Application;
use App\Commands\TgMessageCommand;
use App\Telegram\TelegramApiImp;
use PHPUnit\Framework\TestCase;

class TgMessageCommandTest extends TestCase
{
    public function testRunCallCorrectMessage(): void
    {
        $appMock = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tgMock = $this->getMockBuilder(TelegramApiImp::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tgMock->expects($this->once())
            ->method('getMessage')
            ->with(0)
            ->willReturn([
                'offset' => 0,
                'result' => [1, 'message'],
            ]);

        $command = new TgMessageCommand($appMock);
        $command->setTelegramApi($tgMock);

        $this->expectOutputString('{"offset":0,"result":[1,"message"]}');

        $command->run();
    }

    public function testRunCallIncorrectMessage(): void
    {
        $appMock = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tgMock = $this->getMockBuilder(TelegramApiImp::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tgMock->expects($this->once())
            ->method('getMessage')
            ->with(0)
            ->willReturn([
                'offset' => 0,
                'result' => [1, null],
            ]);

        $command = new TgMessageCommand($appMock);
        $command->setTelegramApi($tgMock);

        $this->expectOutputString('{"offset":0,"result":[1,null]}');

        $command->run();
    }
}