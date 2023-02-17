<?php

namespace App\Repository;

use App\Entity\Viewing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Viewing>
 *
 * @method Viewing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Viewing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Viewing[]    findAll()
 * @method Viewing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Viewing::class);
    }

    public function save(Viewing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Viewing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findViewingsByOwner(int $ownerId): array
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.availaibility', 'a')
            ->leftJoin('a.request', 'r')
            ->leftJoin('r.property', 'p')
            ->leftJoin('p.owner', 'o')
            ->where('o.id = :ownerId')
            ->setParameter('ownerId', $ownerId)
            ->orderBy('a.slot', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
