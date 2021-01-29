<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Running;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Running|null find($id, $lockMode = null, $lockVersion = null)
 * @method Running|null findOneBy(array $criteria, array $orderBy = null)
 * @method Running[]    findAll()
 * @method Running[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RunningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Running::class);
    }

    // /**
    //  * @return Running[] Returns an array of Running objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Running
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
