<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\TaskStatusType;
use App\Repository\TaskRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

readonly class TaskReminderService
{
    public function __construct(
        private TaskRepository  $taskRepository,
        private MailerInterface $mailer,
        private Environment     $twig,
        private LoggerInterface $logger,
        private string          $fromEmail = 'noreply@sebego.co.bw'
    ) {
    }

    public function sendReminders(string $timeframe = 'all'): int
    {
        $sentCount = 0;
        $now = new \DateTimeImmutable();

        $timeframes = $this->getTimeframes($now);

        foreach ($timeframes as $key => $config) {
            if ($timeframe !== 'all' && $timeframe !== $key) {
                continue;
            }

            $tasks = $this->getTasksForTimeframe($config['start'], $config['end']);

            if (empty($tasks)) {
                $this->logger->info("No tasks {$config['label']}", ['timeframe' => $key]);
                continue;
            }

            $this->logger->info("Processing tasks {$config['label']}", [
                'timeframe' => $key,
                'count' => count($tasks)
            ]);

            $tasksByUser = $this->groupTasksByUser($tasks);

            foreach ($tasksByUser as $userData) {
                try {
                    $this->sendReminderEmail(
                        $userData['user'],
                        $userData['tasks'],
                        $config['label'],
                        $config['urgency']
                    );
                    $sentCount++;

                    $this->logger->info('Reminder email sent', [
                        'email' => $userData['user']->getEmail(),
                        'tasks_count' => count($userData['tasks']),
                        'timeframe' => $key
                    ]);
                } catch (\Exception $e) {
                    $this->logger->error('Failed to send reminder email', [
                        'email' => $userData['user']->getEmail(),
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        // Handle overdue tasks separately
        if ($timeframe === 'all' || $timeframe === 'overdue') {
            $overdueCount = $this->sendOverdueReminders();
            $sentCount += $overdueCount;
        }

        $this->logger->info('Task reminder process completed', [
            'total_emails_sent' => $sentCount
        ]);

        return $sentCount;
    }

    private function sendOverdueReminders(): int
    {
        $overdueTasks = $this->getOverdueTasks();

        if (empty($overdueTasks)) {
            return 0;
        }

        $tasksByUser = $this->groupTasksByUser($overdueTasks);
        $sentCount = 0;

        foreach ($tasksByUser as $userData) {
            try {
                $this->sendOverdueEmail($userData['user'], $userData['tasks']);
                $sentCount++;

                $this->logger->warning('Overdue email sent', [
                    'email' => $userData['user']->getEmail(),
                    'tasks_count' => count($userData['tasks'])
                ]);
            } catch (\Exception $e) {
                $this->logger->error('Failed to send overdue email', [
                    'email' => $userData['user']->getEmail(),
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $sentCount;
    }

    private function getTimeframes(\DateTimeImmutable $now): array
    {
        return [
            'today' => [
                'start' => $now->setTime(0, 0),
                'end' => $now->setTime(23, 59, 59),
                'label' => 'Due Today',
                'urgency' => 'high'
            ],
            'tomorrow' => [
                'start' => $now->modify('+1 day')->setTime(0, 0),
                'end' => $now->modify('+1 day')->setTime(23, 59, 59),
                'label' => 'Due Tomorrow',
                'urgency' => 'high'
            ],
            'in_3_days' => [
                'start' => $now->modify('+3 days')->setTime(0, 0),
                'end' => $now->modify('+3 days')->setTime(23, 59, 59),
                'label' => 'Due in 3 Days',
                'urgency' => 'medium'
            ],
            'in_1_week' => [
                'start' => $now->modify('+7 days')->setTime(0, 0),
                'end' => $now->modify('+7 days')->setTime(23, 59, 59),
                'label' => 'Due in 1 Week',
                'urgency' => 'low'
            ],
        ];
    }

    private function getTasksForTimeframe(\DateTimeImmutable $start, \DateTimeImmutable $end): array
    {
        return $this->taskRepository->createQueryBuilder('t')
            ->where('t.taskStatusType = :status')
            ->andWhere('t.dueDate >= :start')
            ->andWhere('t.dueDate <= :end')
            ->andWhere('t.assignedTo IS NOT NULL')
            ->setParameter('status', TaskStatusType::TASK_STATUS_PENDING)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('t.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function getOverdueTasks(): array
    {
        return $this->taskRepository->createQueryBuilder('t')
            ->where('t.taskStatusType = :status')
            ->andWhere('t.dueDate < :now')
            ->andWhere('t.assignedTo IS NOT NULL')
            ->setParameter('status', TaskStatusType::TASK_STATUS_PENDING)
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('t.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function groupTasksByUser(array $tasks): array
    {
        $tasksByUser = [];

        foreach ($tasks as $task) {
            $assignedTo = $task->getAssignedTo();
            if ($assignedTo && $assignedTo->getEmail()) {
                $userId = $assignedTo->getId()->toString();
                if (!isset($tasksByUser[$userId])) {
                    $tasksByUser[$userId] = [
                        'user' => $assignedTo,
                        'tasks' => []
                    ];
                }
                $tasksByUser[$userId]['tasks'][] = $task;
            }
        }

        return $tasksByUser;
    }

    private function sendReminderEmail(User $user, array $tasks, string $timeframeLabel, string $urgency): void
    {
        $html = $this->twig->render('emails/task_reminder.html.twig', [
            'user' => $user,
            'tasks' => $tasks,
            'timeframeLabel' => $timeframeLabel,
            'urgency' => $urgency,
            'isOverdue' => false,
        ]);

        $email = (new Email())
            ->from($this->fromEmail)
            ->to($user->getEmail())
            ->subject("Sebego: Tasks {$timeframeLabel} - " . count($tasks) . " task(s)")
            ->html($html);

        $this->mailer->send($email);
    }

    private function sendOverdueEmail(User $user, array $tasks): void
    {
        $html = $this->twig->render('emails/task_reminder.html.twig', [
            'user' => $user,
            'tasks' => $tasks,
            'timeframeLabel' => 'Overdue',
            'urgency' => 'critical',
            'isOverdue' => true,
        ]);

        $email = (new Email())
            ->from($this->fromEmail)
            ->to($user->getEmail())
            ->subject("⚠️ OVERDUE: " . count($tasks) . " task(s) require immediate attention")
            ->html($html);

        $this->mailer->send($email);
    }
}
