<?php

namespace App\Repository;

use App\Entity\Task;
use App\Enum\TaskStatusType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * Get pending tasks count
     */
    public function getPendingTasksCount(): int
    {
        return $this->count(['taskStatusType' => TaskStatusType::TASK_STATUS_PENDING]);
    }

    /**
     * Get overdue tasks count
     */
    public function getOverdueTasksCount(): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.taskStatusType = :status')
            ->andWhere('t.dueDate < :now')
            ->setParameter('status', TaskStatusType::TASK_STATUS_PENDING)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get upcoming tasks (pending tasks with future due dates)
     */
    public function getUpcomingTasks(int $limit = 10): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.taskStatusType = :status')
            ->andWhere('t.dueDate >= :now')
            ->setParameter('status', TaskStatusType::TASK_STATUS_PENDING)
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('t.dueDate', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get overdue tasks (pending tasks with past due dates)
     */
    public function getOverdueTasks(int $limit = null): array
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.taskStatusType = :status')
            ->andWhere('t.dueDate < :now')
            ->setParameter('status', TaskStatusType::TASK_STATUS_PENDING)
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('t.dueDate', 'ASC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get tasks due this week
     */
    public function getTasksThisWeek(\DateTimeImmutable $weekStart, \DateTimeImmutable $weekEnd): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.dueDate >= :weekStart')
            ->andWhere('t.dueDate <= :weekEnd')
            ->andWhere('t.taskStatusType = :status')
            ->setParameter('weekStart', $weekStart)
            ->setParameter('weekEnd', $weekEnd)
            ->setParameter('status', TaskStatusType::TASK_STATUS_PENDING)
            ->orderBy('t.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get completed tasks count
     */
    public function getCompletedTasksCount(): int
    {
        return $this->count(['taskStatus' => TaskStatusType::TASK_STATUS_COMPLETED]);
    }

    /**
     * Get tasks by status
     */
    public function getTasksByStatus(TaskStatusType $status, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.taskStatusType = :status')
            ->setParameter('status', $status)
            ->orderBy('t.dueDate', 'ASC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
