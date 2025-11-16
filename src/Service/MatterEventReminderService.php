<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\MatterUpdateRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

readonly class MatterEventReminderService
{
    public function __construct(
        private MatterUpdateRepository $matterUpdateRepository,
        private MailerInterface        $mailer,
        private Environment            $twig,
        private LoggerInterface        $logger,
        private string                 $fromEmail = 'noreply@sebego.co.bw'
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

            $events = $this->getEventsForTimeframe($config['start'], $config['end']);

            if (empty($events)) {
                $this->logger->info("No matter events {$config['label']}", ['timeframe' => $key]);
                continue;
            }

            $this->logger->info("Processing matter events {$config['label']}", [
                'timeframe' => $key,
                'count' => count($events)
            ]);

            // Group events by lead lawyer and secretary
            $eventsByUser = $this->groupEventsByResponsibleUsers($events);

            foreach ($eventsByUser as $userData) {
                try {
                    $this->sendEventReminderEmail(
                        $userData['user'],
                        $userData['events'],
                        $config['label'],
                        $config['urgency']
                    );
                    $sentCount++;

                    $this->logger->info('Matter event reminder email sent', [
                        'email' => $userData['user']->getEmail(),
                        'events_count' => count($userData['events']),
                        'timeframe' => $key
                    ]);
                } catch (\Exception $e) {
                    $this->logger->error('Failed to send matter event reminder email', [
                        'email' => $userData['user']->getEmail(),
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        $this->logger->info('Matter event reminder process completed', [
            'total_emails_sent' => $sentCount
        ]);

        return $sentCount;
    }

    private function getTimeframes(\DateTimeImmutable $now): array
    {
        return [
            'today' => [
                'start' => $now->setTime(0, 0),
                'end' => $now->setTime(23, 59, 59),
                'label' => 'Today',
                'urgency' => 'critical'
            ],
            'tomorrow' => [
                'start' => $now->modify('+1 day')->setTime(0, 0),
                'end' => $now->modify('+1 day')->setTime(23, 59, 59),
                'label' => 'Tomorrow',
                'urgency' => 'high'
            ],
            'in_2_days' => [
                'start' => $now->modify('+2 days')->setTime(0, 0),
                'end' => $now->modify('+2 days')->setTime(23, 59, 59),
                'label' => 'In 2 Days',
                'urgency' => 'high'
            ],
            'in_3_days' => [
                'start' => $now->modify('+3 days')->setTime(0, 0),
                'end' => $now->modify('+3 days')->setTime(23, 59, 59),
                'label' => 'In 3 Days',
                'urgency' => 'medium'
            ],
            'in_1_week' => [
                'start' => $now->modify('+7 days')->setTime(0, 0),
                'end' => $now->modify('+7 days')->setTime(23, 59, 59),
                'label' => 'In 1 Week',
                'urgency' => 'low'
            ],
        ];
    }

    private function getEventsForTimeframe(\DateTimeImmutable $start, \DateTimeImmutable $end): array
    {
        return $this->matterUpdateRepository->createQueryBuilder('mu')
            ->leftJoin('mu.matter', 'm')
            ->addSelect('m')
            ->where('mu.eventDate >= :start')
            ->andWhere('mu.eventDate <= :end')
            ->andWhere('mu.eventDate IS NOT NULL')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('mu.eventDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function groupEventsByResponsibleUsers(array $events): array
    {
        $eventsByUser = [];

        foreach ($events as $event) {
            $matter = $event->getMatter();
            if (!$matter) {
                continue;
            }

            // Notify lead lawyer
            $leadLawyer = $matter->getLeadLawyer();
            if ($leadLawyer && $leadLawyer->getEmail()) {
                $userId = $leadLawyer->getId()->toString();
                if (!isset($eventsByUser[$userId])) {
                    $eventsByUser[$userId] = [
                        'user' => $leadLawyer,
                        'events' => []
                    ];
                }
                $eventsByUser[$userId]['events'][] = $event;
            }

            // Notify secretary
            $secretary = $matter->getSecretary();
            if ($secretary && $secretary->getEmail()) {
                $userId = $secretary->getId()->toString();
                if (!isset($eventsByUser[$userId])) {
                    $eventsByUser[$userId] = [
                        'user' => $secretary,
                        'events' => []
                    ];
                }
                // Avoid duplicates if same event already added
                $eventIds = array_map(fn($e) => $e->getId()->toString(), $eventsByUser[$userId]['events']);
                if (!in_array($event->getId()->toString(), $eventIds)) {
                    $eventsByUser[$userId]['events'][] = $event;
                }
            }

            // Notify supporting lawyers (if you have matterLawyers relationship)
            $matterLawyers = $matter->getMatterLawyers();
            foreach ($matterLawyers as $matterLawyer) {
                $lawyer = $matterLawyer->getLawyer();
                if ($lawyer && $lawyer->getEmail()) {
                    $userId = $lawyer->getId()->toString();
                    if (!isset($eventsByUser[$userId])) {
                        $eventsByUser[$userId] = [
                            'user' => $lawyer,
                            'events' => []
                        ];
                    }
                    // Avoid duplicates
                    $eventIds = array_map(fn($e) => $e->getId()->toString(), $eventsByUser[$userId]['events']);
                    if (!in_array($event->getId()->toString(), $eventIds)) {
                        $eventsByUser[$userId]['events'][] = $event;
                    }
                }
            }
        }

        return $eventsByUser;
    }

    private function sendEventReminderEmail(User $user, array $events, string $timeframeLabel, string $urgency): void
    {
        $html = $this->twig->render('emails/matter_event_reminder.html.twig', [
            'user' => $user,
            'events' => $events,
            'timeframeLabel' => $timeframeLabel,
            'urgency' => $urgency,
        ]);

        $email = (new Email())
            ->from($this->fromEmail)
            ->to($user->getEmail())
            ->subject("Sebego: Matter Events {$timeframeLabel} - " . count($events) . " event(s)")
            ->html($html);

        $this->mailer->send($email);
    }
}
