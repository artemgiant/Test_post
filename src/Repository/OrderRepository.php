<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /*
     new orders
    */
    public function getNewOrders($user_id,$maxResult=0)
    {
        $qr = $this->createQueryBuilder('o')
            ->leftJoin('o.orderStatus', 's')
            ->where('o.user = :user_id')
            ->andWhere('o.orderStatus IS NULL or (o.orderStatus IS NOT NULL and (s.status = :status or s.status = :status1))')
            ->setParameter('user_id', $user_id)
            ->setParameter('status', 'new')
            ->setParameter('status1', 'paid')
            ->orderBy('o.createdAt','DESC')
        ;
        if ($maxResult>0)
            return $qr->setMaxResults($maxResult)->getQuery()->getResult();
        else
            return $qr->getQuery();
    }

    public function getSendOrders($user_id,$maxResult=0)
    {
        $qr = $this->createQueryBuilder('o')
            ->leftJoin('o.orderStatus', 's')
            ->where('o.user = :user_id')
            ->andWhere('o.orderStatus IS NOT NULL ')
            ->andWhere('s.status = :status')
            ->setParameter('user_id', $user_id)
            ->setParameter('status', 'complit')
            ->orderBy('o.createdAt','DESC')
        ;
        if ($maxResult>0)
            return $qr->setMaxResults($maxResult)->getQuery()->getResult();
        else
            return $qr->getQuery();
    }

    public function getOrders($user_id)
    {
        $qr = $this->createQueryBuilder('o')
            ->select('o.trackingNumber', 'o.comment', 's.status')
            ->join('o.orderStatus', 's')
            ->where('o.user = :user_id')
            ->setParameter('user_id', $user_id);
        return $qr->getQuery()->getResult();
    }

}
