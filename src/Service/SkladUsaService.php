<?php

namespace App\Service;

use App\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

define("LOG_SKLADUSA", getcwd() . "/../errors-sklad-usa.log");

class SkladUsaService
{

//    protected $api_base_url = 'http://localhost:8080';
    protected $api_base_url = 'https://system.skladusa.com';
    protected $path_econom = '/api/order_expressposhta_econom/';
    protected $path_express = '/api/order_expressposhta_express/';
    protected $api_key = ''; // w

    protected $userId="2";
//    protected $userToken="JWrsTqm4YCpXMvcPFxMUuHNTzrj1ML"; // for userId=2 http://localhost:8080
    protected $userToken="Xubo5iNGuEeRiw4PSedfkdpH2ZyZYs"; // for userId=2 https://test.skladusa.com

    // Send Orders data from Expressposhta to Sklad

    public function __construct()
    {
        $this->setApiKey();
    }

    public function setApiKey()
    {
        $this->api_key = base64_encode($this->userId.":".$this->userToken);
    }

    public function sendOrderToSklad(Order $order){

        $data = new ArrayCollection();

        $data->epOrderId = $order->getId();
        $data->receiverName = $order->getAddresses()->getFullName();
        $data->receiverEmail = $order->getUser()->getEmail();
        $data->receiverAddress = $order->getAddresses()->getAddress();
        $data->receiverCity = $order->getAddresses()->getCity();
        $data->receiverState = $order->getAddresses()->getRegionOblast();
        $data->receiverZip = $order->getAddresses()->getZip();
        $data->receiverCountry = $order->getAddresses()->getCountry()->getShortName();
        //$data->trackingNumber = $order->getTrackingNumber();
        //$data->shippingCompanyToUsa = $order->getCompanySendToUsa();
        //$data->trackingNumberToUsa = $order->getSystemNum();
       // $data->shippingCompanyInUsa = $order->getCompanySendInUsa();
        //$data->trackingNumberInUsa = $order->getSystemNumInUsa();
        $data->comment = $order->getComment();
        $data->address = $order->getAddresses()->getAddress();
        if($order->getOrderType()->getCode() == 'econom'){
            list($lbWeight,$ozWeight)=$this->getWeightInLb($order->getSendDetailWeight());
            $data->weightLb=$lbWeight??0;
            $data->weightOz=$ozWeight??0;
        }
        if($order->getOrderType()->getCode() == 'express'){
            $data->weightkg=$this->getWeightInKg($order->getSendDetailWeight());
        }

        $data->length=$order->getSendDetailLength();
        $data->width=$order->getSendDetailWidth();
        $data->height=$order->getSendDetailHeight();

        $data->productsData = [];
        foreach ($order->getProducts() as $product) {
            $data->productsData[]  = [
                'descrEn' => $product->getDescEn(),
                'descrUa' => $product->getDescUa(),
                'count' => $product->getCount(),
                'price' => $product->getPrice(),
            ];
        }

        $data_json = json_encode($data);
        $headers = array(
            'Authorization: ' . $this->api_key,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json)
        );
        $requestUrl = '';
        if($order->getOrderType()->getCode() == 'econom'){
            $requestUrl = $this->api_base_url.$this->path_econom;
        }
        if($order->getOrderType()->getCode() == 'express'){
            $requestUrl = $this->api_base_url.$this->path_express;
        }

        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_URL,$requestUrl);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlObj,CURLOPT_SSL_VERIFYPEER, false);

        $response  = curl_exec($curlObj);
        curl_close($curlObj);
        unset($curlObj);
        if(json_decode($response)->status != 'success') {
            error_log('------START-----' . date('Y-m-d H:i') . PHP_EOL, 3, LOG_SKLADUSA);
            error_log('------response-----' . $response . PHP_EOL, 3, LOG_SKLADUSA);
        }
        return $response;

    }


    public function getWeightInKg($weigth)
    {
        return $weigth/1000;
    }

    public function getWeightInLb($weigth)
    {
        $ozFullWeight=floor($weigth * 0.035274);
        $lbWeight=floor($ozFullWeight /16);
        $ozWeight= $ozFullWeight-$lbWeight*16;

        return [$lbWeight,$ozWeight];
    }
}