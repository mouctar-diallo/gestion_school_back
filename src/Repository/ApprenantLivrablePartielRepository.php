<?php

namespace App\Repository;

use App\Entity\ApprenantLivrablePartiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApprenantLivrablePartiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApprenantLivrablePartiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApprenantLivrablePartiel[]    findAll()
 * @method ApprenantLivrablePartiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprenantLivrablePartielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApprenantLivrablePartiel::class);
    }

    public function recupereLivrablePartiel($id)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.livrablePartiel = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function recupereFilDiscution($id,$fil)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.livrablePartiel = :val')
            ->andWhere('a.filDiscution = :fil')
            ->setParameter('val', $id)
            ->setParameter('fil', $fil)
            ->getQuery()
            ->getResult()
        ;
    }
}
