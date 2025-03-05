<?php

namespace App\Repository;

use App\Entity\Chantier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chantier>
 */
class ChantierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chantier::class);
    }

    public function countChantiersTermines(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.date_de_fin <= :today')
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countChantiersEnCours(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.date_de_debut <= :today')
            ->andWhere('c.date_de_fin > :today')
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countChantiersPasCommences(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.date_de_debut > :today')
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }


    //    /**
    //     * @return Chantier[] Returns an array of Chantier objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Chantier
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
