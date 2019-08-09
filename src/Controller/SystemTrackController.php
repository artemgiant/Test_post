<?php

namespace App\Controller;

use App\Entity\Order;
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

        if($request->getMethod()== "POST" && (($request->request->get('_token'))!= "BWufe5fBhHD7dqI6Nds3YKOXBHwNuEjj0Kwm")){
            return $this->render("bundles/TwigBundle/Exception/error404.html.twig");
        }
        $OrderReposotory =  $this->getDoctrine()->getRepository(Order::class);
        $Order =  $OrderReposotory->find($request->request->get('id_order'));
        $Order->setSystemNum($request->request->get('systemNum'));
        $entityManager = $this->getDoctrine()->getManager();
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
        $Order =  $OrderReposotory->find($request->request->get('id_order'));
        $Order->setSystemNumInUsa($request->request->get('systemNumInUsa'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Order);
        $entityManager->flush();
        dd('success');
    }


}
