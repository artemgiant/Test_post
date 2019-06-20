<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AddressFormType;
use App\Service\LiqPayService;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/")
 */
class CabinetController extends AbstractController
{
    public $user;
    public $my_address;
    public $optionToTemplate;

    public function getTemplateData()
    {
        $this->user = $this->getUser();
        $this->my_address = $this->getMyAddress($this->user->getId());
        $this->optionToTemplate=[
            'user'=>$this->user,
            'my_address' => $this->my_address
        ];

    }

    public function getMyAddress($user_id)
    {
        $result = $this->getDoctrine()
            ->getRepository(User::class)
            ->getMyAddress($user_id);

        if (!$result)
            return ' ';
        $result['address'] = '';

        if ($result['street'])
            $result['address'].= 'ул. '.$result['street'];
        if ($result['house'])
            $result['address'].= ', д. '.$result['house'];
        if ($result['apartment'])
            $result['address'].= ', кв. '.$result['apartment'];

        return $result;
    }

    /**
     * @Route("/", name="homepage")
     */

    public function homepage()
    {
        return $this->redirectToRoute('post_dashboard');
    }

}

