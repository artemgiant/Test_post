<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $UserBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $UserBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getOrdersWithProducts($user_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT user.first_name, user.last_name, order_statuses.status, order_products.desc_en, order_products.total_summ
                FROM user
                JOIN orders ON user.id = orders.user_id
                JOIN order_statuses ON order_statuses.id = orders.order_status
                JOIN order_products ON order_products.order_id = orders.id
                WHERE orders.user_id = :user_id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $orders = $stmt->fetchAll();

        return $orders;
    }

    public function getMyAddress($user_id)
    {
        $qr = $this->createQueryBuilder('u')
            ->select('a.id', 'u.firstName', 'u.lastName', 'a.zip', 'c.name as country', 'a.city','a.adress')
            ->join('u.addresses', 'a')
            ->join('a.country', 'c')
            ->where('u.id = :user_id AND a.isMyAddress = 1')
            ->setParameter('user_id', $user_id)
        ->setMaxResults(1);
        return $qr->getQuery()->getOneOrNullResult();
    }
}
