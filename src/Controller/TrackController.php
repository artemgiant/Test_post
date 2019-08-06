<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Entity\Order;

/**
 * test
 */
use App\Service\TrackingMoreService;

class TrackController extends CabinetController
{

    public function __construct()
    {}
    /**
     * @Route("/track/{tracnum}")
     */
    public function trackAction(Request $request, TrackingMoreService $trackingMore,$tracnum): Response
    {

        $trNum=$tracnum;
        $errors =[];
        $mess=[];
        if ($trNum)
        {
            $entityManager = $this->getDoctrine()->getManager();

            $order = $entityManager
                ->getRepository(Order::class)
                ->findOneBy(['trackingNumber'=>$trNum]);

            if ($order){
                /* @var $order Order */
                $tracksArray=array(
                    'nova-poshta'=>$order->getTrackingNumber(),
                    "tousa_".$order->getCompanySendToUsa() =>$order->getSystemNum(),
                    "inusa_".$order->getCompanySendInUsa() =>$order->getSystemNumInUsa()
                );
                foreach($tracksArray as $carier=>$trackNum) {
                    if (empty($trackNum)) {
                        $mess[$carier] = [];
                        continue;
                    }
                    $carierCode=str_replace(["tousa_","inusa_"],'',$carier);
                    $info=$trackingMore->getSingleTrackingResult($carierCode,$trackNum,'ru');
                    $infoData=$info['data']??false;
                    if ($infoData && $infoDatatrack=$infoData["origin_info"]??false){
                        $trackinfo=$infoDatatrack["trackinfo"]??false;

                        foreach($trackinfo as $item){
                            $mess[$carier][]=$item;
                        }
                    }

                }
                foreach($mess as &$marr) $marr=array_reverse($marr);
                unset($marr);
            }
        }


        return $this->render('track/index.html.twig', [
            'errors'=>implode($errors,'</br>'),
            'trNum'=> $trNum,
            'items'=>$order,
        ]);
    }

}

