<?php

namespace App\Scheduler\Message;

readonly class SendMatterEventRemindersMessage
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
