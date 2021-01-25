<?php

namespace App\Repository;

use App\Entity\Diary;
use App\Entity\MenchoSamaya;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenchoSamaya|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenchoSamaya|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenchoSamaya[]    findAll()
 * @method MenchoSamaya[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenchoSamayaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenchoSamaya::class);
    }

     /**
      * @return MenchoSamaya[] Returns an array of MenchoSamaya objects
      */

    public function findByDiaryUuid(Diary $diary): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.diary = :val')
            ->setParameter('val', $diary->getUuid())
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?MenchoSamaya
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
