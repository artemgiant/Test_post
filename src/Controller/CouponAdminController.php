<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Coupon;
use Sonata\AdminBundle\Controller\CRUDController;
use App\Entity\Address;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AddressFormType;

final class CouponAdminController extends CRUDController
{


    /**
     * @Route("/post/coupone/ajax", name="coupone_ajax")
     * @param Request $request
     */
    public function getCouponeDataAjax(Request $request)
    {
        $codeCoupon =  $request->request->get('code');
        $CouponObject =  $this->getDoctrine()->getRepository(Coupon::class)->findOneBy(['Code'=>$codeCoupon]);
        $data = array();
        if(!empty($CouponObject)){
            $data = [
                'quantity'=> $CouponObject->getQuantity(),
                'discount'=>$CouponObject->getDiscount()
            ];
            $CouponObject->getCode();
        }
        if(empty($data))$data['error']='error';
        $response = new JsonResponse($data);
       return $response;
    }

}
