<?php

namespace App\Repository;

use App\Entity\MenchoMantra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenchoMantra|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenchoMantra|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenchoMantra[]    findAll()
 * @method MenchoMantra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenchoMantraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenchoMantra::class);
    }

    // /**
    //  * @return MenchoMantra[] Returns an array of MenchoMantra objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenchoMantra
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
