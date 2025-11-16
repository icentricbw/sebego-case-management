<?php

namespace App\Scheduler\Message;

readonly class SendTaskRemindersMessage
{
    public function __construct(
        private string $timeframe = 'all'
    ) {
    }

    public function getTimeframe(): string
    {
        return $this->timeframe;
    }
}
