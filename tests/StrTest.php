<?php

use App\Helpers\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    /**
     * @dataProvider studlyDataProvider
     */
    public function testStudly(string $value, string $studly)
    {
        $str = new Str();

        $result = $str->studly($value);

        self::assertEquals($result, $studly);
    }

        public function studlyDataProvider(): array
    {
        return
            [
                ['handle-events_command', 'HandleEventsCommand'],
                ['handleevents_command', 'HandleeventsCommand'],
                ['handle-eventscommand', 'HandleEventscommand'],
                ['handle-events    command', 'HandleEventsCommand'],
            ]
        ;
    }
}