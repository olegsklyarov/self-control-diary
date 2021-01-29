<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sleeping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sleeping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sleeping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sleeping[]    findAll()
 * @method Sleeping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SleepingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sleeping::class);
    }

    // /**
    //  * @return Sleeping[] Returns an array of Sleeping objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sleeping
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
