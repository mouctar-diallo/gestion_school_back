<?php

namespace App\Repository;

use App\Entity\Promos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promos[]    findAll()
 * @method Promos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promos::class);
    }

    public function ifRefInPromo($idpromo,$idr)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->andWhere('u.referentiels = :p')
            ->setParameter('p',$idr)
            ->setParameter('val',$idpromo  )
            ->getQuery()
            ->getResult()
        ;
    }
}
