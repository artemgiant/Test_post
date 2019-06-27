<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\TransactionLiqPay;
use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AddressFormType;
use App\Service\LiqPayService;
use Symfony\Contracts\Translation\TranslatorInterface;

use App\Controller\CabinetController;


class DefaultController extends CabinetController
{

    public $user;
    public $my_address;

    /**
     * @Route("/post/dashboard", name="post_dashboard")
     */
    public function dashboardAction(): Response
    {
        $this->getTemplateData();
        $this->optionToTemplate['page_id']='post_dashboard';
        $this->optionToTemplate['page_title']='Dashboard';

        $this->user = $this->getUser();
        $my_address = $this->getMyAddress($this->user->getId());
        $ordersNew = $this->getDoctrine()
            ->getRepository(Order::class)
            ->getNewOrders($this->user->getId(),5);
        $ordersSend = $this->getDoctrine()
            ->getRepository(Order::class)
            ->getSendOrders($this->user->getId(),5);

        $payment = $this->getDoctrine()
            ->getRepository(TransactionLiqPay::class)
            ->getNewPayments($this->user->getId(),5);

        return $this->render('cabinet/dashboard/dashboard.html.twig',
            array_merge($this->optionToTemplate,
                [   'ordersNew'=>$ordersNew,
                    'ordersSend'=>$ordersSend,
                    'payment'=>$payment,
                    'addresses'=>$my_address,
                    ]
            ));
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

    /**
     * @Route("post/ajax/set-locale", name="ajax_post_set_locale")
     * @param Request $request
     * @return Response
     */
    public function ajaxSetLocaleAction(Request $request): Response
    {
        $this->getTemplateData();
        $entityManager = $this->getDoctrine()->getManager();
        $errors =[];
            $locale=$request->get('locale',null);
            if (!empty($locale) && !empty($this->user)){
                $this->user->setLocale($locale);
                $this->session->set('_locale', $locale);
                $entityManager->persist($this->user);
                $entityManager->flush();
            }


        return new JsonResponse([true]);

    }
}

