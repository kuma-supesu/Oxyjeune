<?php

namespace App\Repository;

use App\Entity\Heure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Heure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Heure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Heure[]    findAll()
 * @method Heure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Heure::class);
    }

    /*
    public function findOneBySomeField($value): ?Heure
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
