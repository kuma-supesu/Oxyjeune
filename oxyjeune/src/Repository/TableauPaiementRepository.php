<?php

namespace App\Repository;

use App\Entity\TableauPaiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TableauPaiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableauPaiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableauPaiement[]    findAll()
 * @method TableauPaiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauPaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableauPaiement::class);
    }

    /*
    public function findOneBySomeField($value): ?TableauPaiement
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
