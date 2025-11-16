<?php

namespace App\Repository;

use App\Entity\Archive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Archive>
 */
class ArchiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Archive::class);
    }

    /**
     * Get total archived matters count
     */
    public function getArchivedMattersCount(): int
    {
        return $this->count([]);
    }

    /**
     * Get recently archived matters
     */
    public function getRecentlyArchived(int $limit = 10): array
    {
        return $this->findBy(
            [],
            ['archivedDate' => 'DESC'],
            $limit
        );
    }

    /**
     * Get archives by batch
     */
    public function getArchivesByBatch(string $batch): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.batch = :batch')
            ->setParameter('batch', $batch)
            ->orderBy('a.boxNumber', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get archives this week
     */
    public function getArchivesThisWeek(\DateTimeImmutable $weekStart, \DateTimeImmutable $weekEnd): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.archivedDate >= :weekStart')
            ->andWhere('a.archivedDate <= :weekEnd')
            ->orderBy('a.archivedDate', 'DESC')
            ->setParameter('weekStart', $weekStart)
            ->setParameter('weekEnd', $weekEnd)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get archives count this week
     */
    public function getArchivesThisWeekCount(\DateTimeImmutable $weekStart, \DateTimeImmutable $weekEnd): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.archivedDate >= :weekStart')
            ->andWhere('a.archivedDate <= :weekEnd')
            ->setParameter('weekStart', $weekStart)
            ->setParameter('weekEnd', $weekEnd)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get all batches with counts
     */
    public function getBatchesWithCounts(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.batch, COUNT(a.id) as archiveCount')
            ->groupBy('a.batch')
            ->orderBy('a.batch', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
