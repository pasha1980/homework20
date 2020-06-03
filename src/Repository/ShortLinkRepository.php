<?php

namespace App\Repository;

use App\Entity\ShortLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShortLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortLink[]    findAll()
 * @method ShortLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortLink::class);
    }

    // /**
    //  * @return ShortLink[] Returns an array of ShortLink objects
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
    public function findOneBySomeField($value): ?ShortLink
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
