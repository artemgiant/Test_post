<?php

namespace App\Repository;

use App\Entity\PriceWeightEconomVip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PriceWeightEconomVip|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceWeightEconomVip|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceWeightEconomVip[]    findAll()
 * @method PriceWeightEconomVip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceWeightEconomVipRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PriceWeightEconomVip::class);
    }

    // /**
    //  * @return PriceWeightEconomVip[] Returns an array of PriceWeightEconomVip objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PriceWeightEconomVip
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findPriceByWeight(float $weight): ?PriceWeightEconomVip
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.max_weight >= :DetailWeight')
            ->setParameter('DetailWeight', $weight)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;

    }
}
