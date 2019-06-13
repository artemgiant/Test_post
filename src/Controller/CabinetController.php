<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CabinetController extends AbstractController
{
    private $user;

    /**
     * @Route("/post/dashboard", name="post_dashboard")
     */
    public function dashboardAction(): Response
    {
        $this->user = $this->getUser();
        $my_address = $this->getMyAddress($this->user->getId());
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->getOrders($this->user->getId());

//        var_dump($my_address);
//        die();

        return $this->render('cabinet/dashboard/dashboard.html.twig', [
            'user' => $this->user,
            'my_address' => $my_address,
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/post/profile", name="post_profile")
     */
    public function profileAction(): Response
    {
        $this->user = $this->getUser();
        $my_address = $this->getMyAddress($this->user->getId());

        return $this->render('cabinet/profile/profile.html.twig', [
            'user' => $this->user,
            'my_address' => $my_address,
            ]);
    }

    /**
     * @Route("/post/update_profile", name="post_update_profile")
     */
    public function profileUpdateAction(Request $request)
    {
//        $user = new User();
//        $form = $this->createForm(RegistrationFormType::class, $user);
//        $form->handleRequest($request);

        return new JsonResponse(['Success' => 'Success']);
    }

    /**
     * @Route("/post/parcels", name="post_parcels")
     */
    public function parcelsAction(): Response
    {
        $this->user = $this->getUser();
        $my_address = $this->getMyAddress($this->user->getId());
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->getOrders($this->user->getId());

        return $this->render('cabinet/parcels/parcels.html.twig', [
            'user' => $this->user,
            'my_address' => $my_address,
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/post/addresses", name="post_addresses")
     */
    public function addressesAction(): Response
    {
        $this->user = $this->getUser();
        $my_address = $this->getMyAddress($this->user->getId());

        return $this->render('cabinet/addresses/addresses.html.twig', [
            'user' => $this->user,
            'my_address' => $my_address,
        ]);
    }

    public function getMyAddress($user_id)
    {
        return $this->getDoctrine()
            ->getRepository(User::class)
            ->getMyAddress($user_id);
    }
}

