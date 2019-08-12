<?php

namespace App\Service;


/**
 * Class SMSSendService
 */
class DhlDeliveryService
{

    /* for Test */
    private $dhlSiteIdTest = "v62_zUR99NQjNx";
    private $dhlSitePassTest = "8SvzYICaHT";

    /* for Prod */
    private $dhlSiteId = "v62_efrQQrWbM0";
    private $dhlSitePass = "OvVIpOwqgZ";

//        private $dhlAccount = "381109442";
    private $dhlAccount = "381113146";

    private $dhlAccountUSA = "965305675";

    private $accountCode = 'UA';

    private $dhlProdUrl = "https://xmlpi-ea.dhl.com/XMLShippingServlet";

    private $dhlTestUrl = "http://xmlpitest-ea.dhl.com/XMLShippingServlet";

    private $dhlSendBox = false;
    //    private $dhlSendBox=true;

    private $dhlUrl;
    private $logged = false;
    private $VipMarkup = 20;
    private $Markup = 40;


    public $dhlToCountry;
    public $dhlErrors;
    public $sendTo;
    public $dhlfromCountry;
    public $dhlFromZip;

    public $elType;
    /*
     *  prod Url https://xmlpi-ea.dhl.com/XMLShippingServlet
     *
     * test Url https://xmlpitest-ea.dhl.com/XMLShippingServlet
     *
     * */
    private $container;

    public function __construct($dhlSendBoxAddress = null)
    {
        if (!empty($dhlSendBoxAddress)) {
            $this->dhlFromZip = $dhlSendBoxAddress['zip'];
            $this->dhlfromCountry = $dhlSendBoxAddress['from'];
            $this->dhlToCountry = $dhlSendBoxAddress['to'];
            $this->dhlfromCity = $dhlSendBoxAddress['city'];
            $this->dhlfromStreet = $dhlSendBoxAddress['street'];
        }
        if ($this->dhlSendBox) {
            $this->dhlSiteId = $this->dhlSiteIdTest;
            $this->dhlSitePass = $this->dhlSitePassTest;
            $this->dhlUrl = $this->dhlTestUrl;
        } else $this->dhlUrl = $this->dhlProdUrl;

    }


    /**
     * @param string $text
     * @param
     *
     *
     *
     * @return array
     */
    public function GetCountrySelectForm($name,$text=null,TranslatorInterface $tt )
    {

        $countryList=$this->getEm()->getRepository(Country::class)->findAll();
        if (empty($name)) $name="UNITED STATES OF AMERICA";

        $dataView = $this->templating->render('AppBundle:Backend/dhlServied:countrySelect.html.twig', array(
            'countryList' => $countryList,
            'name' =>$name,
            'text'=>$tt->translate($text)
        ));

        return $dataView;
    }

    /**
     * @param string $text
     * @param User $user
     *
     *
     *
     * @return float
     */
    public function CostSipment($sum,$user)
    {
        if (empty($user)) return false;

        /* @var $setting Settings */
        $setting = $this->getEm()->getRepository("AppBundle:Settings")->find(1);
        $addProcent=$setting->getPersentAddDhlNorm();
        /*
             if ($user->getVip()===true)
             {
                 $addProcent=$setting->getPersentAddDhlVip();
             }
         */
        if ($this->accountCode=="UA"){
            $addProcent=$setting->getPersentAddDhlUa();
        }elseif ($this->accountCode=="US"){
            $addProcent=$setting->getPersentAddDhlUs();
        }

        return round(($sum*$addProcent)/100+$sum,2);
    }





    /**
     * @param $object
     *
     * @return mixed
     */
    public function getAccountId($object=null)
    {
        $countryCode = null;
        $countryFrom = $countryFromCode = $countryFromString = null;

        $weight=0;
        $country=null;

        if (true) {
            $country            = 'UA';
            $countryFromString  = 'UA';
        }
        elseif (false)
            $country            = 'UNITED STATES OF AMERICA';

        // getting country from code
//        if($countryFromString) {
//            /** @var DhlContryRegionBase $countryFrom */
//            $countryFrom    = $this
//                ->getEm()
//                ->getRepository('AppBundle:DhlContryRegionBase')
//                ->getDhlCountry($countryFromString)
//            ;
//
//            $countryFromCode = $countryFrom ? $countryFrom->getCountyCode() : '';
//        }
        // end getting country from code

        if (empty($this->dhlToCountry) && !empty($country)) {
            $dhlToCountry='US';
            $this->dhlToCountry=$dhlToCountry;
            if (!empty($dhlToCountry)){
                $countryCode='US';
            }
        }elseif(!empty($this->dhlToCountry)){
            $countryCode='US';
        }
        $weightN=$gWeight=0;
        if ((float)"1">0) $weightN=(float) "1";
        $gWeight = ((float) "1" * (float) "1"* (float) "1") / 5000;
        $weight=max($weightN,$gWeight);

        if(
            $countryFromCode == 'BY'
        ) {
            $this->accountCode = "US";
            return $this->dhlAccountUSA;
        }
        elseif (
            !empty($countryCode)
            &&
            $countryCode=='US'
            &&
            $weight>0
        ){
            if ($weight<=4.5){ $this->accountCode="US"; return $this->dhlAccountUSA;}
            elseif ($weight>4.5 && $weight<=5) { $this->accountCode="UA";return $this->dhlAccount;}
            elseif ($weight>5 && $weight<=10){ $this->accountCode="US"; return $this->dhlAccountUSA;}
            elseif ($weight>10 && $weight<20){ $this->accountCode="US"; return $this->dhlAccountUSA;}
        }
        elseif (
            !empty($countryCode)
            &&
            $countryCode=='CA'
            &&
            $weight>0
        ){
            if ($weight<=1.5) { $this->accountCode="US";return $this->dhlAccountUSA;}
            elseif ($weight>1.5 && $weight<=10) { $this->accountCode="UA";return $this->dhlAccount;}
        }
        elseif(
            !empty($countryCode)
            &&
            in_array($countryCode, [
                'JP','AU','BH','GI','NZ','MY','MX','SG','AE','ID','TR','AL','BD',
                'BA','CA','IC','CN','GG','HK','IN','ID','IL','JE','KR','KW','LI',
                'MO','MK','MY','ME','OM','PH','QA','SA','RS','ZA','LK','TW','TH','TR','VN'
            ])
            &&
            $weight>0
            &&
            $weight<=1
        ){
            $this->accountCode="US";
            return $this->dhlAccountUSA;
        }
        elseif(
            !empty($countryCode)
            &&
            in_array($countryCode, [
                'JP','AU','BH','GI','NZ','MY','MX','SG','AE','ID','TR','AL','BD',
                'BA','IC','CN','GG','HK','IN','ID','IL','JE','KR','KW','LI','MO',
                'MK','MY','ME','OM','PH','QA','SA','RS','ZA','LK','TW','TH','TR','VN'
            ])
            &&
            $weight>0
            &&
            $weight<=1.5
        ){
            $this->accountCode="US";
            return $this->dhlAccountUSA;
        }
        else { $this->accountCode="UA";return $this->dhlAccount;}

        return $this->dhlAccount;
    }

    /**
     *
     * @param Shipment|OrdersDHL|OrdersDhlNoTr $object
     *
     * @return array
     */
    public function getDHLPrice($object)
    {
        /* @var $object Shipment */
        $return =false;

        $shipSumm=0;
        $prArr=[];
        $declareCount=0;
        $declareSum=0;
        //tt1
        if (true
        ){
            if (1){

                    $declareSum=1;
                }
            }

        elseif (false) {
            if (1) {
                    $declareSum =1;
                    $declareCount =1;

            }
        }


        $erType=1;

        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><p:DCTRequest schemaVersion=\"2.0\" xsi:schemaLocation=\"http://www.dhl.com DCT-req.xsd\" xmlns:p=\"http://www.dhl.com\" xmlns:p1=\"http://www.dhl.com/datatypes\" xmlns:p2=\"http://www.dhl.com/DCTRequestdatatypes\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"></p:DCTRequest>");
        $GetQuote = $xml->addChild('GetQuote',
            null, "_");

        $Request =$GetQuote->addChild('Request',
            null, "_");

        $ServiceHeader=$Request->addChild("ServiceHeader");

        $ServiceHeader->addChild("MessageTime", date("c"));
        $ServiceHeader->addChild("MessageReference", substr("SkladUSA_dhl_to_rate_shipment_#12_".time()."_".time(),0,32));
        $ServiceHeader->addChild("SiteID", $this->dhlSiteId);
        $ServiceHeader->addChild("Password", $this->dhlSitePass);
        $MetaData=$Request->addChild("MetaData");
        $MetaData->addChild("SoftwareName","3PV");
        $MetaData->addChild("SoftwareVersion","6.2");


        $From=$GetQuote->addChild("From", null,"_");
        if (!empty($this->dhlfromCountry)) {
            $From->addChild("CountryCode", "UA");
        }
        $From->addChild("Postalcode", "55430");
        $From->addChild("City", "Minneapolis");
        $this->getAccountId($object);
        $BkgDetails=$GetQuote->addChild("BkgDetails", null,"_");

        $BkgDetails->addChild("PaymentCountryCode",$this->accountCode);
        $BkgDetails->addChild("Date",date('Y-m-d'));
        $BkgDetails->addChild("ReadyTime",'PT10H21M');
        $BkgDetails->addChild("ReadyTimeGMTOffset",date('O'));
        $BkgDetails->addChild("DimensionUnit",'CM');
        $BkgDetails->addChild("WeightUnit",'KG');
        $Pieces=$BkgDetails->addChild("Pieces", null,"_");
        $x=1;
        do {
            $Piece=$Pieces->addChild("Piece");
            $Piece->addChild("PieceID",$x);
            //$Piece->addChild("PackageType","YP");
            if ($x==1) {
                $Piece->addChild("Height", "1");
                $Piece->addChild("Depth","1");
                $Piece->addChild("Width", "1");
                $Piece->addChild("Weight", "1.000");
            }else{
                $Piece->addChild("Height", 1);
                $Piece->addChild("Depth", 1);
                $Piece->addChild("Weight", 0.001);
                $Piece->addChild("Width", 1);
            }
        } while (1);
        $BkgDetails->addChild("PaymentAccountNumber",$this->getAccountId());
        $BkgDetails->addChild("IsDutiable",'Y');
        $QtdShp1=$BkgDetails->addChild("QtdShp", null,"_");
        $QtdShp1->addChild("GlobalProductCode",'P');

        /*
        $QtdShp2=$BkgDetails->addChild("QtdShp", null,"_");
        $BkgDetails->addChild("GlobalProductCode",'Y');
        */

        $To=$GetQuote->addChild("To", null,"_");
//tt2
        if (1) {

            if (!empty($this->dhlToCountry)) {
                $To->addChild("CountryCode","US");
            }
            $To->addChild("Postalcode", "55430");
            $To->addChild("City", "Minneapolis");

        }
        elseif(0) {
            if (!empty($this->dhlToCountry)) {
                $To->addChild("CountryCode",  $this->sendTo['CountryCode']);
            }
            $To->addChild("Postalcode", $this->sendTo['PostalCode']);

            $To->addChild("City",$this->sendTo['City']);
        }


        $Dutiable=$GetQuote->addChild("Dutiable", null,"_");
        $Dutiable->addChild("DeclaredCurrency",'USD');
        $Dutiable->addChild("DeclaredValue",$declareSum);

        /* old code */

        $xml = $xml->asXML();

        $xml=str_replace('xmlns="_"', "", $xml);
        $xml=str_replace("\n", "", $xml);

        $ch = curl_init($this->dhlUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);

        $http_headers = array(
            'Content-type: ' . 'text/xml'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $result = @curl_exec($ch);
        $movies = new \SimpleXMLElement($result);

        $GetQuoteResponse=$movies->GetQuoteResponse??false;
        $BkgDetails1=($GetQuoteResponse)?$GetQuoteResponse->BkgDetails??false:false;
        $QtdShp1=($BkgDetails1)?$BkgDetails1->QtdShp??false:false;
        $QtdSInAdCur=($QtdShp1)?$QtdShp1->QtdSInAdCur??false:false;
        if (
        $QtdSInAdCur
        ){

            foreach($QtdSInAdCur as $test){
                if (isset($test) && isset($test->CurrencyCode) && (string)$test->CurrencyCode=='USD'){
                    $shipSumm=(float)$test->TotalAmount;
                }
            }
        }
        $note=$GetQuoteResponse->Note??false;
        if ($note){
            $status=(string)$note->ActionStatus??false;
            if ($status && $status=='Failure'){
                $Condition=$note->Condition??false;
                if ($Condition->ConditionData)
                    $this->dhlErrors=(string)$Condition->ConditionData;
            }
        }

        if ($shipSumm==0 && empty($this->dhlErrors)){

            $Response=$movies->Response??false;

            $statusResponce=$Response->Status??false;



            $ActionStatus=(string)$statusResponce->ActionStatus??false;
            if ($ActionStatus && $ActionStatus=='Error') {
                $Condition=$statusResponce->Condition??false;
                if ($Condition){
                    $ConditionData=(string)$Condition->ConditionData??false;
                    if ($ConditionData) $this->dhlErrors=(string)$Condition->ConditionData;
                }
            }
        }
dd($shipSumm);
        return $shipSumm;
    }


    /**
     * @param $object
     *
     * @return array
     */
    public function getOrderProcesFee($object)
    {

        $weight=0;
        $fee=0;
        if ($object instanceof  OrdersDhlNoTr  ) {

            $weightN=$gWeight=0;
            if ((float)$object->getSendDetailWeight()>0) $weightN=(float)$object->getSendDetailWeight();
            $gWeight = ((float)$object->getSendDetailWidth() * (float)$object->getSendDetailHeight() * (float)$object->getSendDetailLength()) / 5000;
            $weight=max($weightN,$gWeight);
            if (
                $weight>0
                &&
                $weight<=2
            )$fee=2;
            elseif (
                $weight>2
                &&
                $weight<=5
            )$fee=3;
            elseif (
                $weight>5
                &&
                $weight<=10
            )$fee=5;
            elseif($weight>10)  $fee=10;
        }
        return $fee;
    }



    public function calculatePrice($object,$noFee=true)
    {
        $price=0;
        $em=$this->getEm();
        $tt=$this->container->get('app.translate');
        $hasErors=false;
        $trErrors=[];
        if ( !empty($object->getFromCountry())) {
            $dhlfromCountry=$em->getRepository('AppBundle:DhlContryRegionBase')->getDhlCountry($object->getFromCountry());

            $this->dhlfromCountry=$dhlfromCountry;
            if (empty($dhlfromCountry)){
                $hasErors=true;
                $trErrors[]=$this->GetCountrySelectForm('dhlfromCountry',"SelectFromCountryIf");
            }
        }else{

            $hasErors=true;
            $trErrors[]=$this->GetCountrySelectForm('dhlfromCountry',"SelectFromCountry");
        }

        if (!empty($object->getCountry())) {
            $dhlToCountry=$em->getRepository('AppBundle:DhlContryRegionBase')->getDhlCountry($object->getCountry());
            $this->dhlToCountry=$dhlToCountry;

            if (empty($dhlToCountry)){
                $hasErors=true;
                $trErrors[]=$this->GetCountrySelectForm('dhlToCountry',"SelectToCountryIf");
            }
        }else{
            $hasErors=true;
            $trErrors[]=$this->GetCountrySelectForm('dhlToCountry',"SelectToCountry");
        }
        if ($hasErors===false){
            $this->getAccountId($object);
            $price=$this->getDHLPrice($object);
        }

        if ($price==0){
            return $this->dhlErrors;
        }
        elseif($noFee){
            //if ($this->logged===true) error_log('------' . date('Y-m-d H:i') ." | noFee | ".$price." | ".$this->CostSipment($price,$object->getUser()). PHP_EOL, 3, LOG_FILE1);
            return $this->CostSipment($price,$object->getUser());
        }
        else {
            $returnSumm=$this->CostSipment($price,$object->getUser())??0;
            $returnFee=$this->getOrderProcesFee($object)??0;
            // if ($this->logged===true) error_log('------' . date('Y-m-d H:i') ." | Fee | ".$price." | ".$returnSumm."|".$returnFee."|".($returnSumm+$returnFee)."|". PHP_EOL, 3, LOG_FILE1);
            return $returnSumm+$returnFee;
        }
    }

    public function  setElType($elType){
        $this->elType=$elType;
        return $this;
    }
}