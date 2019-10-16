<?php

namespace App\Repository;

use App\Entity\PriceForDeliveryType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PriceForDeliveryType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceForDeliveryType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceForDeliveryType[]    findAll()
 * @method PriceForDeliveryType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceEconomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PriceForDeliveryType::class);
    }


    public function findPriceByWeight(float $weight,int $deliveryType)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.max_weight >= :DetailWeight')
            ->andWhere('p.ordertype = :deliveryType')
            ->setParameter('DetailWeight', $weight)
            ->setParameter('deliveryType', $deliveryType)
            ->setMaxResults(1)
            ->orderBy('p.max_weight','ASC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findMaxWeight()
    {
        return $this->createQueryBuilder('p')
            ->select('max(p.max_weight)')
            ->getQuery()
            ->getResult()[0][1]
            ;
    }
    public function findPriceExpress()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.ordertype = :type')
            ->setParameter('type','2')
            ->setMaxResults(1)
            ->getQuery()  ->getOneOrNullResult()
//            ->getOneOrNullResult()
            ;
    }

}
