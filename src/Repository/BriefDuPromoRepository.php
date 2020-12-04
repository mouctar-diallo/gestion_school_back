<?php

namespace App\Repository;

use App\Entity\BriefDuPromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BriefDuPromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method BriefDuPromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method BriefDuPromo[]    findAll()
 * @method BriefDuPromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefDuPromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BriefDuPromo::class);
    }

    //liste les birefs d'une promo
    public function listBriefDunePromo($idpromo)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.promos = :val')
            ->andWhere('u.statut = :s')
            ->setParameter('val',$idpromo)
            ->setParameter('s',"encours")
            ->getQuery()
            ->getResult()
        ;
    }

    // Fonction qui permet de voir si un brief est dans une promo 
    public function ifBriefInPromo($idpromo,$idb)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.brief', 'br')
            ->andWhere('br.id = :val')
            ->andWhere('u.promos = :p') 
            ->setParameter('p',$idpromo)
            ->setParameter('val', $idb)
            ->getQuery()
            ->getResult()
        ;
    }
}
