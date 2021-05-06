<?php

namespace App\Repository;

use App\Entity\CardInBuylist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CardInBuylist|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardInBuylist|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardInBuylist[]    findAll()
 * @method CardInBuylist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardInBuylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardInBuylist::class);
    }

    // /**
    //  * @return CardInBuylist[] Returns an array of CardInBuylist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CardInBuylist
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
