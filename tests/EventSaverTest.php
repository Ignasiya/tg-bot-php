<?php

use App\Actions\EventSaver;
use App\Models\Event;
use PHPUnit\Framework\TestCase;

/**
 * @covers EventSaver
 */
class EventSaverTest extends TestCase
{
    /**
     * @dataProvider eventDtoDataProvider
     */
    public function testHandleCallCorrectInsertInModel(array $eventDto, array $expectedArray): void
    {
        $mock = $this->getMockBuilder(Event::class)
            ->setMethods(['insert'])
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('insert')
            ->with(
                "name, text, receiver_id, minute, hour, day, month, day_of_week",
                $expectedArray
            );

        $eventSaver = new EventSaver($mock);
        $eventSaver->handle($eventDto);
    }

    public static function eventDtoDataProvider(): array
    {
        return [
            [
                [

                    'name' => 'some-name',

                    'text' => 'some-text',

                    'receiver_id' => 'some-reciver',

                    'minute' => 'some-minute',

                    'hour' => 'some-hour',

                    'day' => 'some-day',

                    'month' => 'some-month',

                    'day_of_week' => 'some-week'
                ],
                [

                    'some-name',

                    'some-text',

                    'some-reciver',

                    'some-minute',

                    'some-hour',

                    'some-day',

                    'some-month',

                    'some-week'
                ],
            ],
            [
                [

                    'name' => 'some-name',

                    'text' => 'some-text',

                    'receiver_id' => 'some-reciver',

                    'minute' => null,

                    'hour' => null,

                    'day' => null,

                    'month' => null,

                    'day_of_week' => null
                ],
                [

                    'some-name',

                    'some-text',

                    'some-reciver',

                    null,

                    null,

                    null,

                    null,

                    null
                ],
            ]
        ];
    }
}