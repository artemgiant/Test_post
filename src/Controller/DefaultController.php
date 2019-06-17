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


class DefaultController extends CabinetController
{

    public $user;
    public $my_address;



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


        return $this->render('cabinet/dashboard/dashboard.html.twig', [
            'user' => $this->user,
            'my_address' => $my_address,
            'orders' => $orders,
        ]);
    }





    /**
     * @Route("/", name="homepage")
     */

    public function homepage()
    {
        return $this->redirectToRoute('post_dashboard');
    }

    /**
     * @Route("/payment/check", name="payment_check")
     *
     */
    public function liqPayAction(Request $request,LiqPayService $liqPayService)
    {

        $data = $request->request->all();

        $response = '';

        try {

            $liqPayService->check($data);

            $response = 'success';
        } catch (Exception $e) {

        } finally {

        }

        return new Response($response);
    }

    /**
     * @Route("/payment/result", name="payment_result")
     *
     */
    public function paymentResultAction(Request $request,LiqPayService $liqPayService,TranslatorInterface $translateService)
    {

        $data = $request->request->all();

        $this->addFlash(
            'success',
            $translateService->trans("LiqPay Sucesss")
        );
        return $this->redirect($this->generateUrl('post_dashboard'));
    }

}

