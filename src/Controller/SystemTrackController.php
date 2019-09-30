<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderStatus;
use App\Admin\OrdersAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SystemTrackController extends AbstractController
{

    /**
     * @Route("/system/track/outside", name="system_track_outside")
     */
    public function OutsideAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($request->getMethod()== "POST" && (($request->request->get('_token'))!= "BWufe5fBhHD7dqI6Nds3YKOXBHwNuEjj0Kwm")){
            return $this->render("bundles/TwigBundle/Exception/error404.html.twig");
        }
        $OrderReposotory =  $this->getDoctrine()->getRepository(Order::class);
        /** @var Order $Order */
        $Order =  $OrderReposotory->find($request->request->get('id_order'));


        $Order->setSystemNum($request->request->get('systemNum'));
        $orderStatus = $entityManager->getRepository(OrderStatus::class)->findOneBy(['status' => 'complit']);
        $Order->setOrderStatus($orderStatus);
            $companyTmp=$request->request->get('company',false);

            $company = ($companyTmp && in_array($companyTmp,OrdersAdmin::CARRIER_CODES))?$companyTmp:'usps';
            $Order->setCompanySendToUsa($company);
        $entityManager->persist($Order);
        $entityManager->flush();

        dd('success');

    }

    /**
     * @Route("/system/track/inside", name="system_track_inside")
     */
    public function InsideAction(Request $request)
    {
        if($request->getMethod()== "POST" && (($request->request->get('_token'))!= "BWufe5fBhHD7dqI6Nds3YKOXBHwNuEjj0Kwm")){
            return $this->render("bundles/TwigBundle/Exception/error404.html.twig");
        }
        $OrderReposotory =  $this->getDoctrine()->getRepository(Order::class);

        /** @var Order $Order */

        $Order =  $OrderReposotory->find($request->request->get('id_order'));
        $Order->setSystemNumInUsa($request->request->get('systemNumInUsa'));
        $entityManager = $this->getDoctrine()->getManager();
        $companyTmp=$request->request->get('company',false);

        $company = ($companyTmp && in_array(OrdersAdmin::CARRIER_CODES))?$companyTmp:'usps';
        $Order->setCompanySendInUsa($company);
        $entityManager->persist($Order);
        $entityManager->flush();
        dd('success');
    }


}
