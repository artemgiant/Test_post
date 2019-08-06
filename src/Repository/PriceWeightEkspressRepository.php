<?php

namespace App\Repository;

use App\Entity\PriceWeightEkspress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PriceWeightEkspress|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceWeightEkspress|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceWeightEkspress[]    findAll()
 * @method PriceWeightEkspress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceWeightEkspressRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PriceWeightEkspress::class);
    }

    // /**
    //  * @return PriceWeightEkspress[] Returns an array of PriceWeightEkspress objects
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
    public function findOneBySomeField($value): ?PriceWeightEkspress
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
