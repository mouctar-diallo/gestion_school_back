<?php

namespace App\Repository;

use App\Entity\Referentiels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Referentiels|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referentiels|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referentiels[]    findAll()
 * @method Referentiels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentielsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referentiels::class);
    }

  //permet de verifier si le referentiel est associé a un groupe de competences
  public function ifGroupeInReferentiel($idref,$idgroupe)
  {
      return $this->createQueryBuilder('r')
          ->innerJoin('r.groupeCompetences', 'grp')
          ->andWhere('grp.id = :val')
          ->andWhere('r.id = :id')
          ->setParameter('id', $idref)
          ->setParameter('val', $idgroupe)
          ->getQuery()
          ->getOneOrNullResult()
      ;
  ;
  }

    /*
    public function findOneBySomeField($value): ?Referentiels
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
