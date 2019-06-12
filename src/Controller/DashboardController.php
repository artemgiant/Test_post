<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
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
        $user   = $this->getUser();;

        return $this->render('dashboard/dashboard.html.twig', ['user' => $user]);
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

    /**
     * @Route("/", name="homepage")
     */

    public function homepage()
    {

        return $this->redirectToRoute('post_dashboard');
    }
}
