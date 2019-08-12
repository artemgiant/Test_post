<?php

namespace App\Repository;

use App\Entity\Country;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;



class CountryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function getShortNameCountry($From,$To)
    {
        $qr = $this->createQueryBuilder('o')
            ->select('o.name, o.shortName')
            ->andWhere('o.name = :from')
            ->setParameter('from', $From);

        $FromAndTo[] = $qr->getQuery()->getResult()[0]['shortName'];
        $to = $this->createQueryBuilder('a')
            ->select('a.name, a.shortName')
            ->where('a.id = :to')
            ->setParameter('to', $To);

    $FromAndTo[] = $to->getQuery()->getResult()[0]['shortName'];
         return $FromAndTo;

    }
}
//$queryBuilder->andWhere('r.winner IN (:ids)')
