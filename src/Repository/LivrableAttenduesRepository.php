<?php

namespace App\Repository;

use App\Entity\LivrableAttendues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LivrableAttendues|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivrableAttendues|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivrableAttendues[]    findAll()
 * @method LivrableAttendues[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivrableAttenduesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivrableAttendues::class);
    }

    // /**
    //  * @return LivrableAttendues[] Returns an array of LivrableAttendues objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LivrableAttendues
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
