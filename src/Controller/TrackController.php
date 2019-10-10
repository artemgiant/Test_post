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
$crierLink=[
    'usps'=>"https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=#num#",
    'dhl'=>"https://www.dhl.com/en/express/tracking.html?AWB=#num#&brand=DHL",
    //"apc"=>,
    "fedex"=>"https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=#num#&cntry_code=us&locale=en_US",
  //  "ups"=>,
];
        $trNum=$traknum;

        $errors =[];
        $mess=[];
        $urltousa="";
        $trNumtousa="";
        $urlinusa="";
        $trNuminusa="";
        if ($trNum)
        {
            $entityManager = $this->getDoctrine()->getManager();

            $order = $entityManager
                ->getRepository(Order::class)
                ->findOneBy(['trNum'=>$trNum]);
            if ($order){
                /* @var $order Order */
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
                        $urltousa=($crierLink[$carierCode])?str_replace('#num#',$trackNum,$crierLink[$carierCode]):'';
                        $trNumtousa=$trackNum;

                    }
                    if (strpos($carier,"inusa_")!==false){
                        $urlinusa=($crierLink[$carierCode])?str_replace('#num#',$trackNum,$crierLink[$carierCode]):'';
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
            'trNum'=> $trNum,
            'items'=>$mess,
            'page_id'=>'post_find',
            'urltousa'=>$urltousa,
            'trNumtousa'=>$trNumtousa,
            'urlinusa'=>$urlinusa,
            'trNuminusa'=>$trNuminusa
        ]);
    }

    public function getCompanyNameByTrNum($trNum){

        $curier='';
        if(preg_match("/^[0-9]{22}$/", trim($trNum)) && strlen (trim($trNum))==22) {
            $curier="usps";
        }
        elseif(preg_match("/^[0-9]{10}$/", trim($trNum)) && strlen (trim($trNum))==10) {
            $curier="dhl";
        }
        elseif(preg_match("/^[0-9]{13}$/", trim($trNum)) && strlen (trim($trNum))==13) {
            $curier="apc";
        }
        elseif(preg_match("/^[0-9]{12}$/", trim($trNum)) && strlen (trim($trNum))==12) {
            $curier="fedex";
        }
        elseif (strlen (trim($trNum))==18) {
            $curier="ups";
        }

      return $curier;
    }

}

