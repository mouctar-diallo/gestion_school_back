<?php

namespace App\Repository;

use App\Entity\CompetencesValides;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompetencesValides|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetencesValides|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetencesValides[]    findAll()
 * @method CompetencesValides[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetencesValidesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetencesValides::class);
    }

    public function ifRefAppInPromo($ida,$idpromo,$idr)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.promos = :val')
            ->andWhere('u.apprenant = :a')
            ->andWhere('u.referentiels = :p')
            ->setParameter('p',$idr)
            ->setParameter('a',$ida)
            ->setParameter('val',$idpromo )
            ->getQuery()
            ->getResult()
        ;
    }

    public function ifRefInPromo($idpromo,$idr)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.promos = :val')
            ->andWhere('u.referentiels = :p')
            ->setParameter('p',$idr)
            ->setParameter('val',$idpromo  )
            ->getQuery()
            ->getResult()
        ;
    }
}
