<?php

namespace App\Repository;

use App\Entity\MatterClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MatterClient>
 */
class MatterClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatterClient::class);
    }

    /**
     * Get all clients for a specific matter
     */
    public function findByMatter(string $matterId): array
    {
        return $this->createQueryBuilder('mc')
            ->leftJoin('mc.client', 'c')
            ->addSelect('c')
            ->where('mc.matter = :matterId')
            ->setParameter('matterId', $matterId)
            ->orderBy('mc.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all matters for a specific client
     */
    public function findByClient(string $clientId): array
    {
        return $this->createQueryBuilder('mc')
            ->leftJoin('mc.matter', 'm')
            ->addSelect('m')
            ->where('mc.client = :clientId')
            ->setParameter('clientId', $clientId)
            ->orderBy('mc.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Check if a client is already linked to a matter with a specific role
     */
    public function existsByMatterClientAndRole(string $matterId, string $clientId, string $role): bool
    {
        $result = $this->createQueryBuilder('mc')
            ->select('COUNT(mc.id)')
            ->where('mc.matter = :matterId')
            ->andWhere('mc.client = :clientId')
            ->andWhere('mc.clientRole = :role')
            ->setParameter('matterId', $matterId)
            ->setParameter('clientId', $clientId)
            ->setParameter('role', $role)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }
}
