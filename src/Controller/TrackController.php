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
     * @Route("/track/{traknum}")
     */
    public function trackAction(Request $request, TrackingMoreService $trackingMore,$traknum): Response
    {

        $trNum=$traknum;

        $errors =[];
        $mess=[];
        $urltousa="";
        $trNumtousa="";
        $companyToUSA=$companyInUSA="";
        $urlinusa="";
        $trNuminusa="";
        $express=0;
        if ($trNum)
        {
            $entityManager = $this->getDoctrine()->getManager();

            $order = $entityManager
                ->getRepository(Order::class)
                ->findOneBy(['trNum'=>$trNum]);

            if ($order){
                /* @var $order Order */
                $express=($order->getOrderType()->getCode()=='express')?1:0;
                $companyToUSA=$this->getCompanyNameByTrNum($order->getSystemNum());
                $companyInUSA=$this->getCompanyNameByTrNum($order->getSystemNumInUsa());
                $tracksArray=array(
                    'nova-poshta'=>$order->getTrackingNumber(),
                    "tousa_".$companyToUSA =>$order->getSystemNum(),
                    "inusa_".$companyInUSA =>$order->getSystemNumInUsa()
                );


                foreach($tracksArray as $carier=>$trackNum) {
                    if (empty($trackNum)) {
                        $mess[$carier] = [];
                        continue;
                    }
                    if (empty($carier)) {
                        $mess[] = [];
                        continue;
                    }
                    $carierCode=str_replace(["tousa_","inusa_"],'',$carier);
                    if (strpos($carier,"tousa_")!==false){
                        $urltousa=($this->carierLink[$carierCode])?str_replace('#num#',$trackNum,$this->carierLink[$carierCode]):'';
                        $trNumtousa=$trackNum;

                    }
                    if (strpos($carier,"inusa_")!==false){
                        $urlinusa=($this->carierLink[$carierCode])?str_replace('#num#',$trackNum,$this->carierLink[$carierCode]):'';
                        $trNuminusa=$trackNum;

                    }
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
            'express'=>$express,
            'trNum'=> $trNum,
            'items'=>$mess,
            'page_id'=>'post_find',
            'urltousa'=>$urltousa,
            'companyToUSA'=>strtoupper($companyToUSA),
            'trNumtousa'=>$trNumtousa,
            'urlinusa'=>$urlinusa,
            'trNuminusa'=>$trNuminusa,
            'companyInUSA'=>strtoupper($companyInUSA),
        ]);
    }



}

