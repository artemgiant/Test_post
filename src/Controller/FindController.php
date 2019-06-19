<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Entity\User;
use App\Entity\Order;


use App\Service\TrackingMoreService;
/**
 * @Route("/post/find")
 */
class FindController extends CabinetController
{
    public $user;

    public function __construct()
    {}
    /**
     * @Route("/", name="post_find")
     */
    public function profileAction(Request $request, TrackingMoreService $trackingMore): Response
    {
        $this->user = $this->getUser();
        $trNum=$request->request->get('tracnum',false);

        $errors =[];
        $mess=[];
        if ($trNum)
        {
            $entityManager = $this->getDoctrine()->getManager();

            $order = $entityManager
                ->getRepository(Order::class)
                ->findOneBy(['trNum'=>$trNum]);
            if ($order){
            /* @var $order Order */
                $trackNum=$order->getSystemNum();
                if (empty($trackNum)){
                    $trackNum=$order->getTrackingNumber();
                }
                if (!empty($trackNum)){
                    $carier=$trackingMore->detectCarrier($trackNum);
                    $carierData=$carier['data']??false;
                    if ($carierData && $carierCode=$carier['code']??false)
                    {
                       $info=$trackingMore->getSingleTrackingResult($carierCode,$trackNum,'ru');
                        $infoData=$info['data']??false;
                        if ($infoData && $infoDatatrack=$infoData["origin_info"]??false){
                            $trackinfo=$infoDatatrack["trackinfo"]??false;

                            foreach($trackinfo as $item){
                                $mess[]=$item;
                            }
                        }
                    }
                }
            }
        }


        return $this->render('cabinet/find/index.html.twig', [
            'user' => $this->user,
            'errors'=>implode($errors,'</br>'),
            'trNum'=> $trNum,
            'items'=>$mess,
            'my_address' => $this->getMyAddress($this->user->getId()),
            'page_id'=>'post_find'
        ]);
    }

}

