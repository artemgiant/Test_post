<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractController
{
    
    /**
     * @Route("/post/dashboard", name="post_dashboard")
     */
    public function dashboardAction(): Response
    {
        $user   = $this->getUser();

//        $orders = $this->getDoctrine()
//            ->getRepository(User::class)
//            ->getOrdersWithProducts($user->getId());

        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->getOrders($user->getId());

//        var_dump($orders);
//        die();

        return $this->render('dashboard/dashboard.html.twig', [
            'user' => $user,
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/post/profile", name="post_profile")
     */
    public function profileAction(): Response
    {
        $user   = $this->getUser();

        return $this->render('profile/profile.html.twig', ['user' => $user]);
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
}
