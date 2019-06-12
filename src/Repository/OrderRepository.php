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
