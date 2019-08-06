<?php

namespace App\Repository;

use App\Entity\VipPriceWeightEkspress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VipPriceWeightEkspress|null find($id, $lockMode = null, $lockVersion = null)
 * @method VipPriceWeightEkspress|null findOneBy(array $criteria, array $orderBy = null)
 * @method VipPriceWeightEkspress[]    findAll()
 * @method VipPriceWeightEkspress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VipPriceWeightEkspressRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VipPriceWeightEkspress::class);
    }

    // /**
    //  * @return VipPriceWeightEkspress[] Returns an array of VipPriceWeightEkspress objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VipPriceWeightEkspress
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
