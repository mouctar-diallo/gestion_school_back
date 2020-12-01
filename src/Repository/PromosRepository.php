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

 
  
    

    /*
    public function findOneBySomeField($value): ?Promos
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
