<?php

namespace App\Repository;

use App\Entity\CardInDeck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CardInDeck|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardInDeck|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardInDeck[]    findAll()
 * @method CardInDeck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardInDeckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardInDeck::class);
    }

    // /**
    //  * @return CardInDeck[] Returns an array of CardInDeck objects
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
    public function findOneBySomeField($value): ?CardInDeck
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
