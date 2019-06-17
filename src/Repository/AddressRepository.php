<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;



class AddressRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Address::class);
    }



    public function getAdressList($user)
    {

        $qr = $this->createQueryBuilder('address')
            ->select('address')
            ->where('address.user = :user')
            ->andWhere('address.my_address is null')
            ->setParameter('user', $user);
        return $qr->getQuery();
    }
}
