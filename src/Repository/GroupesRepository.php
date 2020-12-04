<?php

namespace App\Repository;

use App\Entity\Groupes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Groupes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupes[]    findAll()
 * @method Groupes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupes::class);
    }

    public function ifGroupeInPromo($idg,$idp)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.promos', 'p')
            ->andWhere('g.id = :val')
            ->andWhere('p.id = :g')
            ->setParameter('val', $idg)
            ->setParameter('g', $idp)
            ->getQuery()
            ->getResult()
        ;
    }

  //permet de verifier si l'apprenant se trouve dans le groupe
  public function ifApprenantInGroupe($idapprenant,$idgroupe)
  {
      return $this->createQueryBuilder('u')
          ->innerJoin('u.apprenants', 'p')
          ->andWhere('u.id = :val')
          ->andWhere('p.id = :a')
          ->setParameter('a',$idapprenant)
          ->setParameter('val', $idgroupe)
          ->getQuery()
          ->getResult()
      ;
  }
}
