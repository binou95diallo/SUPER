<?php

namespace App\Repository;

use App\Entity\BU;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BU|null find($id, $lockMode = null, $lockVersion = null)
 * @method BU|null findOneBy(array $criteria, array $orderBy = null)
 * @method BU[]    findAll()
 * @method BU[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BURepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BU::class);
    }

    // /**
    //  * @return BU[] Returns an array of BU objects
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

    /*
    public function findOneBySomeField($value): ?BU
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
