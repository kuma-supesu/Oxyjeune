<?php

namespace App\Repository;

use App\Entity\TableauLigne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TableauLigne|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableauLigne|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableauLigne[]    findAll()
 * @method TableauLigne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauLigneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableauLigne::class);
    }

    /*
    public function findOneBySomeField($value): ?TableauLigne
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
