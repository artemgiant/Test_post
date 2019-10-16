<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\ExpressDeliveryPrice;
use App\Entity\PriceForDeliveryType;
use Sonata\AdminBundle\Controller\CRUDController;
use App\Entity\Address;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AddressFormType;

final class CouponController extends CabinetController
{


    /**
     * @Route("/post/coupone/ajax", name="coupone_ajax")
     * @param Request $request
     */
    public function getCouponeDataAjax(Request $request)
    {
        $codeCoupon =  $request->request->get('code');
        $DeliveryType = (int) $request->request->get('DeliveryType');
        $DHLPrice = (int) $request->request->get('DHLPrice');

        $weightPriceEl = $this->getDoctrine()
            ->getRepository(PriceForDeliveryType::class)
            ->findPriceByWeight((float)$request->request->get('Weight'),$DeliveryType);
        $priceCoupon = '';
        if($weightPriceEl)$priceCoupon = $weightPriceEl->getVipPrice();
       $CouponObject =  $this->getDoctrine()->getRepository(Coupon::class)->findOneBy(['Code'=>$codeCoupon]);

//        if(empty($DHLPrice)){
//            $settingsMarkup=$this->getDoctrine()->getRepository(ExpressDeliveryPrice::class)->getDHLMarkup();
//            $vipMarkup=$settingsMarkup['DHLMarkupForVip']??20;
//
//        }
        $data = array();
        if(!empty($CouponObject)){
            $data = [
                'quantity'=> $CouponObject->getQuantity(),
                'priceCoupon'=>$priceCoupon
            ];
        }
        if(empty($data))$data['error']='error';
        $response = new JsonResponse($data);
       return $response;
    }

}
