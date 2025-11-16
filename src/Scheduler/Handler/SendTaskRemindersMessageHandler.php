<?php

namespace App\Scheduler\Handler;

use App\Scheduler\Message\SendTaskRemindersMessage;
use App\Service\TaskReminderService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendTaskRemindersMessageHandler
{
    public function __construct(
        private readonly TaskReminderService $taskReminderService
    ) {
    }

    public function __invoke(SendTaskRemindersMessage $message): void
    {
        $this->taskReminderService->sendReminders($message->getTimeframe());
    }
}
