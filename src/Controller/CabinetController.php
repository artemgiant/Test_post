<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AddressFormType;
use App\Service\LiqPayService;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/")
 */
class CabinetController extends AbstractController
{

    public $user;
    public $my_address;
    public $optionToTemplate;
    public $carierLink=[
'usps'=>"https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=#num#",
'dhl'=>"https://www.dhl.com/en/express/tracking.html?AWB=#num#&brand=DHL",
    //"apc"=>,
"fedex"=>"https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=#num#&cntry_code=us&locale=en_US",
    //  "ups"=>,
];
    public function getTemplateData()
    {
        $this->user = $this->getUser();

        if (!empty($this->user)) {
            $this->my_address = $this->getMyAddress($this->user->getId());
            $this->optionToTemplate = [
                'user' => $this->user,
                'my_address' => $this->my_address
            ];
        }else{
            return $this->redirectToRoute('user_login');
        }

    }

    public function getMyAddress($user_id)
    {

        $result = $this->getDoctrine()
            ->getRepository(User::class)
            ->getMyAddress($user_id);
        if (!$result)
            return ' ';
        $result['address'] = '';

//        if ($result['street'])
//            $result['address'].= 'ул. '.$result['street'];
//        if ($result['house'])
//            $result['address'].= ', д. '.$result['house'];
//        if ($result['apartment'])
//            $result['address'].= ', кв. '.$result['apartment'];

        return $result;
    }

    /**
     * @Route("/", name="homepage")
     */

    public function homepage()
    {
        return $this->redirectToRoute('post_dashboard');
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

