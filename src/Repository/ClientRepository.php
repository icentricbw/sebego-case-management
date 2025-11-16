<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Get total clients count
     */
    public function getTotalClientsCount(): int
    {
        return $this->count([]);
    }

    /**
     * Get recent clients
     */
    public function getRecentClients(int $limit = 10): array
    {
        return $this->findBy(
            [],
            ['createdAt' => 'DESC'],
            $limit
        );
    }

    /**
     * Get clients created this week
     */
    public function getClientsThisWeek(\DateTimeImmutable $weekStart, \DateTimeImmutable $weekEnd): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.createdAt >= :weekStart')
            ->andWhere('c.createdAt <= :weekEnd')
            ->orderBy('c.createdAt', 'DESC')
            ->setParameter('weekStart', $weekStart)
            ->setParameter('weekEnd', $weekEnd)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get clients count created this week
     */
    public function getClientsThisWeekCount(\DateTimeImmutable $weekStart, \DateTimeImmutable $weekEnd): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.createdAt >= :weekStart')
            ->andWhere('c.createdAt <= :weekEnd')
            ->setParameter('weekStart', $weekStart)
            ->setParameter('weekEnd', $weekEnd)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
