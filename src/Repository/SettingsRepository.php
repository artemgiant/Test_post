<?php

namespace App\Repository;

use App\Entity\Settings;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;



class SettingsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Settings::class);
    }

    public function getDHLMarkup()
    {
        $qr = $this->createQueryBuilder('o')
            ->select('o.code, o.value')
            ->andWhere('o.code in (:code)')
            ->setParameter('code', ['DHLMarkup','DHLMarkupForVip']);

    $resultQr = $qr->getQuery()->getResult();
        $result=[];
    foreach($resultQr as $res){
        $result[$res['code']??null]=$res['value']??null;
    }

         return $result;

    }
}

