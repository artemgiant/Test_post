<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\TransactionLiqPay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionLiqPayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TransactionLiqPay::class);
    }



    public function getListPayments($user_id)
    {
        $qr = $this->createQueryBuilder('o')
            ->where('o.user = :user_id')
            ->setParameter('user_id', $user_id)
            ->orderBy('o.createdAt','DESC')
        ;
        return $qr->getQuery();
    }

    public function getNewPayments($user_id)
    {
        $qr = $this->createQueryBuilder('o')
            ->where('o.user = :user_id')
            ->setParameter('user_id', $user_id)
            ->setMaxResults(10)
            ->orderBy('o.createdAt','DESC')
        ;
        return $qr->getQuery()->getResult();
    }


}
