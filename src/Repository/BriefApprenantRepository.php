<?php

namespace App\Repository;

use App\Entity\BriefApprenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BriefApprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method BriefApprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method BriefApprenant[]    findAll()
 * @method BriefApprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefApprenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BriefApprenant::class);
    }

    // /**
    //  * @return BriefApprenant[] Returns an array of BriefApprenant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

   // liste des briefs des apprennants d une promo 
   public function listBriefAppDunePromo($idpromo)
   {
       return $this->createQueryBuilder('u')
           ->innerJoin('u.briefDuPromo', 'br')
           ->andWhere('br.promos= :val')
           ->setParameter('val',$idpromo)
           ->getQuery()
           ->getResult()
       ;
   }
}
