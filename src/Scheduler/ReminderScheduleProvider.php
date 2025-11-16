<?php

namespace App\Scheduler;

use App\Scheduler\Message\SendMatterEventRemindersMessage;
use App\Scheduler\Message\SendTaskRemindersMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('reminders')]
class ReminderScheduleProvider implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())
            // ========================================
            // TASK REMINDERS
            // ========================================

            // Morning digest - 7:00 AM every day (Tasks)
            ->add(
                RecurringMessage::cron('0 7 * * *', new SendTaskRemindersMessage('today'))
            )
            ->add(
                RecurringMessage::cron('0 7 * * *', new SendTaskRemindersMessage('tomorrow'))
            )
            ->add(
                RecurringMessage::cron('0 7 * * *', new SendTaskRemindersMessage('overdue'))
            )

            // Afternoon reminder - 2:00 PM (Overdue tasks only)
            ->add(
                RecurringMessage::cron('0 14 * * *', new SendTaskRemindersMessage('overdue'))
            )

            // Weekly task reminders - Monday 7:00 AM
            ->add(
                RecurringMessage::cron('0 7 * * 1', new SendTaskRemindersMessage('in_1_week'))
            )

            // 3-day task reminders - 7:00 AM every day
            ->add(
                RecurringMessage::cron('0 7 * * *', new SendTaskRemindersMessage('in_3_days'))
            )

            // ========================================
            // MATTER EVENT REMINDERS
            // ========================================

            // Morning digest - 7:30 AM every day (Matter Events)
            ->add(
                RecurringMessage::cron('30 7 * * *', new SendMatterEventRemindersMessage('today'))
            )
            ->add(
                RecurringMessage::cron('30 7 * * *', new SendMatterEventRemindersMessage('tomorrow'))
            )
            ->add(
                RecurringMessage::cron('30 7 * * *', new SendMatterEventRemindersMessage('in_2_days'))
            )

            // 3-day matter event reminders
            ->add(
                RecurringMessage::cron('30 7 * * *', new SendMatterEventRemindersMessage('in_3_days'))
            )

            // Weekly matter event reminders - Monday 7:30 AM
            ->add(
                RecurringMessage::cron('30 7 * * 1', new SendMatterEventRemindersMessage('in_1_week'))
            );
    }
}
