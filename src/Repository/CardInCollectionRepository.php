<?php

namespace App\Repository;

use App\Entity\CardInCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CardInCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardInCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardInCollection[]    findAll()
 * @method CardInCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardInCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardInCollection::class);
    }

    // /**
    //  * @return CardInCollection[] Returns an array of CardInCollection objects
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
    public function findOneBySomeField($value): ?CardInCollection
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
