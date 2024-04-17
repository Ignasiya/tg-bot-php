<?php

use App\Application;
use App\Commands\HandleEventsCommand;
use PHPUnit\Framework\TestCase;

class HandleEventsCommandTest extends TestCase
{
    /**
     * @dataProvider eventDtoDataProvider
     */
    public function testShouldEventBeRanReceiveEventDtoAndReturnCorrectBool(array $event, bool $shouldEventBeRan): void
    {
        $handleEventsCommand = new HandleEventsCommand(new Application(dirname(__DIR__)));

        $result = $handleEventsCommand->shouldEventBeRan($event);

        self::assertEquals($result, $shouldEventBeRan);
    }

    public static function eventDtoDataProvider(): array
    {
        return [
            [
                [
                    'minute' => (int)date("i"),
                    'hour' => (int)date("H"),
                    'day' => (int)date("d"),
                    'month' => (int)date("m"),
                    'day_of_week' => (int)date("w")
                ],
                true
            ],
            [
                [
                    'minute' => (int)date("i"),
                    'hour' => (int)date("H"),
                    'day' => (int)date("d"),
                    'month' => (int)date("m"),
                    'day_of_week' => null
                ],
                false
            ],
            [
                [
                    'minute' => (int)date("i"),
                    'hour' => (int)date("H"),
                    'day' => (int)date("d"),
                    'month' => null,
                    'day_of_week' =>  (int)date("w")
                ],
                false
            ],
            [
                [
                    'minute' => (int)date("i"),
                    'hour' => (int)date("H"),
                    'day' => null,
                    'month' => (int)date("m"),
                    'day_of_week' => (int)date("w")
                ],
                false
            ],
            [
                [
                    'minute' => (int)date("i"),
                    'hour' => null,
                    'day' => (int)date("d"),
                    'month' => (int)date("m"),
                    'day_of_week' => (int)date("w")
                ],
                false
            ],
            [
                [
                    'minute' => null,
                    'hour' => (int)date("H"),
                    'day' => (int)date("d"),
                    'month' => (int)date("m"),
                    'day_of_week' => (int)date("w")
                ],
                false
            ]
        ];
    }
}