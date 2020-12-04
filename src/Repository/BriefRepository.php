<?php

namespace App\Repository;

use App\Entity\Brief;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brief|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brief|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brief[]    findAll()
 * @method Brief[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brief::class);
    }

    public function ListeBrouillon($idf,$statut)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.formateurs= :val')
            ->andWhere('u.etatBrief = :b')
            ->setParameter('val', $idf)
            ->setParameter('b', $statut)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Brief
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
