<?php

namespace App\Repository;

use App\Entity\Matter;
use App\Enum\StatusType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matter>
 */
class MatterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matter::class);
    }

    /**
     * Count active matters
     */
    public function getActiveMattersCount(): int
    {
        return $this->count(['statusType' => StatusType::ACTIVE]);
    }

    /**
     * Count matters filed this week
     */
    public function getMattersThisWeekCount(\DateTimeImmutable $weekStart, \DateTimeImmutable $weekEnd): int
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->where('m.filingDate >= :weekStart')
            ->andWhere('m.filingDate <= :weekEnd')
            ->setParameter('weekStart', $weekStart)
            ->setParameter('weekEnd', $weekEnd)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get recent matters
     */
    public function getRecentMatters(int $limit = 5): array
    {
        return $this->findBy(
            [],
            ['createdAt' => 'DESC'],
            $limit
        );
    }

    /**
     * Get matters filed this week
     */
    public function getMattersThisWeek(\DateTimeImmutable $weekStart, \DateTimeImmutable $weekEnd, int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.filingDate >= :weekStart')
            ->andWhere('m.filingDate <= :weekEnd')
            ->orderBy('m.filingDate', 'DESC')
            ->setParameter('weekStart', $weekStart)
            ->setParameter('weekEnd', $weekEnd)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get matters count grouped by case type
     */
    public function getMattersByCaseType(): array
    {
        return $this->createQueryBuilder('m')
            ->select('ct.name as caseType, COUNT(m.id) as matterCount')
            ->leftJoin('m.caseType', 'ct')
            ->where('m.statusType = :status')
            ->setParameter('status', StatusType::ACTIVE)
            ->groupBy('ct.id')
            ->orderBy('matterCount', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get matters by status
     */
    public function getMattersByStatus(StatusType $status, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('m')
            ->where('m.statusType = :status')
            ->setParameter('status', $status)
            ->orderBy('m.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get matters count by status
     */
    public function getMattersCountByStatus(StatusType $status): int
    {
        return $this->count(['statusType' => $status]);
    }

    /**
     * Generate next file number
     */
    public function getNextFileNumber(): int
    {
        $qb = $this->createQueryBuilder('m');

        $maxFileNumber = $qb->select('MAX(m.fileNumber)')
            ->getQuery()
            ->getSingleScalarResult();

        return ($maxFileNumber ?? 0) + 1;
    }
}
