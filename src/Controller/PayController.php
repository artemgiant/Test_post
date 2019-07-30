<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\TransactionLiqPay;
use App\Controller\CabinetController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AddressFormType;

use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/post/payment")
 */
class PayController extends CabinetController
{

    /**
     * @Route("/", name="post_payment")
     */
    public function addressesAction(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $this->getTemplateData();
        $this->optionToTemplate['page_id']='post_payment';
        $this->optionToTemplate['page_title']='Payment List';

        $entityManager = $this->getDoctrine()->getManager();

        $paymentsListQuery=$entityManager->getRepository(TransactionLiqPay::class)
                       ->getNewPayments($user->getId());

        $paymentsList = $paginator->paginate(
            $paymentsListQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('cabinet/payment/payments.html.twig', array_merge($this->optionToTemplate,['items'=>$paymentsList]));
    }


}

