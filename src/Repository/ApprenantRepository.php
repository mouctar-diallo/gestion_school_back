<?php

namespace App\Repository;

use App\Entity\Apprenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Apprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apprenant[]    findAll()
 * @method Apprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apprenant::class);
    }

    
    public function ifApprenantDansPromo($idpromo,$idapprenant)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->andWhere('u.promos = :p')
            ->setParameter('p',$idpromo)
            ->setParameter('val', $idapprenant)
            ->getQuery()
            ->getResult()
        ;
    }
}
