<?php

namespace App\Scheduler\Handler;

use App\Scheduler\Message\SendMatterEventRemindersMessage;
use App\Service\MatterEventReminderService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendMatterEventRemindersMessageHandler
{
    public function __construct(
        private readonly MatterEventReminderService $matterEventReminderService
    ) {
    }

    public function __invoke(SendMatterEventRemindersMessage $message): void
    {
        $this->matterEventReminderService->sendReminders($message->getTimeframe());
    }
}
