<?php

namespace App\Repository;

use App\Entity\PriceWeightEconom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PriceWeightEconom|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceWeightEconom|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceWeightEconom[]    findAll()
 * @method PriceWeightEconom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceWeightEconomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PriceWeightEconom::class);
    }

    // /**
    //  * @return PriceWeightEconom[] Returns an array of PriceWeightEconom objects
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
    public function findOneBySomeField($value): ?PriceWeightEconom
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findPriceByWeight(float $weight): float
    {
        $weight_price = $this->createQueryBuilder('p')
            ->select('min(p.max_weight), p.price')
            ->where('p.max_weight >= :DetailWeight')
            ->setParameter('DetailWeight', $weight)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return $weight_price[0]['price'];
    }
}
