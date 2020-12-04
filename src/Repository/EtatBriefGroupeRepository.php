<?php

namespace App\Repository;

use App\Entity\EtatBriefGroupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtatBriefGroupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatBriefGroupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatBriefGroupe[]    findAll()
 * @method EtatBriefGroupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatBriefGroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatBriefGroupe::class);
    }

        //permet de verifier si le groupe se troupe dans la promo
        public function ifStatutEncours($idgroupe)
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.statut = :s')
                ->andWhere('u.groupe = :g')
                ->setParameter('s',"encours")
                ->setParameter('g',$idgroupe)
                ->getQuery()
                ->getResult()
            ;
        }
    /*
    public function findOneBySomeField($value): ?EtatBriefGroupe
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
