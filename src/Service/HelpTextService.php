<?php

namespace App\Service;

use App\Entity\FormHelps;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

use Twig\Environment;

class HelpTextService
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    private $twig;

    public function __construct(
        EntityManagerInterface $entityManager,
        Environment $twig
        )
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }



    public function getTextByCode($code=null)
    {
        if (!empty($code)){
            /** @var FormHelps $helpText */
            $helpText=$this->entityManager->getRepository(FormHelps::class)->findOneBy(["code"=>$code]);
            if ($helpText){
                return $this->twig->render('system/toolTip.html.twig', [
                    'helpText'      =>$helpText->getText()
                ]);

            }
            else return '';
        }
        else return '';
    }
}
