<?php

namespace App\Repository;

use App\Entity\Availaibility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Availaibility>
 *
 * @method Availaibility|null find($id, $lockMode = null, $lockVersion = null)
 * @method Availaibility|null findOneBy(array $criteria, array $orderBy = null)
 * @method Availaibility[]    findAll()
 * @method Availaibility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvailaibilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Availaibility::class);
    }

    public function save(Availaibility $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Availaibility $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAvailaibilityByPropertyAndLodger(int $propertyId, int $lodgerId): Availaibility|array
    {
        return $this->createQueryBuilder('a')
                ->andWhere('a.property = :propertyId')
                ->andWhere('a.lodger = :lodgerId')
                ->orderBy('a.slot', 'ASC')
                ->setParameters([
                    'propertyId' => $propertyId,
                    'lodgerId' => $lodgerId
                ])
                ->getQuery()
                ->getResult();
    }

}
