<?php

namespace App\Repository;

use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Request>
 *
 * @method Request|null find($id, $lockMode = null, $lockVersion = null)
 * @method Request|null findOneBy(array $criteria, array $orderBy = null)
 * @method Request[]    findAll()
 * @method Request[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Request::class);
    }

    public function save(Request $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Request $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findRequestsByPropertyId(string $propertyId): Request|array
    {
        return $this->createQueryBuilder('r')
                ->leftJoin('r.property', 'p')
                ->andWhere('p.id = :propertyId')
                ->setParameter('propertyId', $propertyId)
                ->orderBy('r.created_at')
                ->getQuery()
                ->getResult();
    }

    public function findRequestsByOwnerId(string $ownerId): Request|array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.property', 'p')
            ->leftJoin('p.owner', 'o')
            ->where('o.id = :ownerId')
            ->setParameter('ownerId', $ownerId)
            ->orderBy('r.created_at')
            ->getQuery()
            ->getResult();
    }


    public function findRequestsByLodgerId(string $lodgerId): Request|array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.lodger = :lodgerId')
            ->setParameter('lodgerId', $lodgerId)
            ->orderBy('r.created_at')
            ->getQuery()
            ->getResult();
    }

}
