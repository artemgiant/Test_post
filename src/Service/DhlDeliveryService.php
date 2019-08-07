<?php

namespace App\Service;


/**
 * Class SMSSendService
 */
class DhlDeliveryService
{
    private $templating;


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

    public function __construct($c,$dhlSendBoxAddress = null)
    {

        $this->setContainer($c);
        if (!empty($dhlSendBoxAddress)) {
            $this->dhlSiteId = $this->dhlSiteIdTest;
            $this->dhlSitePass = $this->dhlSitePassTest;
            $this->dhlUrl = $this->dhlTestUrl;
            $this->dhlFromZip =$dhlSendBoxAddress['zip'];
            $this->dhlfromCountry =$dhlSendBoxAddress['country'];
            $this->dhlfromCity =$dhlSendBoxAddress['city'];
            $this->dhlfromStreet =$dhlSendBoxAddress['street'];
        }

    }


    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->container;
    }


    /**
     * @param $object
     *
     * @return mixed
     */
    public function getAccountId($object)
    {


        $countryCode = null;
        $countryFrom = $countryFromCode = $countryFromString = null;

        $weight = 0;
        $country = null;

        if ($object instanceof OrdersDHL || $object instanceof OrdersDhlNoTr) {
            $country = $object->getCountry();
            $countryFromString = $object->getFromCountry();
        } elseif ($object instanceof Shipment)
            $country = 'UNITED STATES OF AMERICA';

        // getting country from code
        if ($countryFromString) {
            /** @var DhlContryRegionBase $countryFrom */
            $countryFrom = $this
                ->getEm()
                ->getRepository('AppBundle:DhlContryRegionBase')
                ->getDhlCountry($countryFromString);

            $countryFromCode = $countryFrom ? $countryFrom->getCountyCode() : '';
        }

        // end getting country from code
        if (empty($this->dhlToCountry) && !empty($country)) {
            $dhlToCountry = $this->getEm()->getRepository('AppBundle:DhlContryRegionBase')->getDhlCountry($country);
            $this->dhlToCountry = $dhlToCountry;
            if (!empty($dhlToCountry)) {
                $countryCode = $dhlToCountry->getCountyCode();
            }
        } elseif (!empty($this->dhlToCountry)) {
            $countryCode = $this->dhlToCountry->getCountyCode();
        }
        $weightN = $gWeight = 0;
        if ((float)$object->getSendDetailWeight() > 0) $weightN = (float)$object->getSendDetailWeight();
        $gWeight = ((float)$object->getSendDetailWidth() * (float)$object->getSendDetailHeight() * (float)$object->getSendDetailLength()) / 5000;
        $weight = max($weightN, $gWeight);

        if (
            $countryFromCode == 'BY'
        ) {
            $this->accountCode = "US";
            return $this->dhlAccountUSA;
        } elseif (
            !empty($countryCode)
            &&
            $countryCode == 'US'
            &&
            $weight > 0
        ) {
            if ($weight <= 4.5) {
                $this->accountCode = "US";
                return $this->dhlAccountUSA;
            } elseif ($weight > 4.5 && $weight <= 5) {
                $this->accountCode = "UA";
                return $this->dhlAccount;
            } elseif ($weight > 5 && $weight <= 10) {
                $this->accountCode = "US";
                return $this->dhlAccountUSA;
            } elseif ($weight > 10 && $weight < 20) {
                $this->accountCode = "US";
                return $this->dhlAccountUSA;
            }
        } elseif (
            !empty($countryCode)
            &&
            $countryCode == 'CA'
            &&
            $weight > 0
        ) {
            if ($weight <= 1.5) {
                $this->accountCode = "US";
                return $this->dhlAccountUSA;
            } elseif ($weight > 1.5 && $weight <= 10) {
                $this->accountCode = "UA";
                return $this->dhlAccount;
            }
        } elseif (
            !empty($countryCode)
            &&
            in_array($countryCode, [
                'JP', 'AU', 'BH', 'GI', 'NZ', 'MY', 'MX', 'SG', 'AE', 'ID', 'TR', 'AL', 'BD',
                'BA', 'CA', 'IC', 'CN', 'GG', 'HK', 'IN', 'ID', 'IL', 'JE', 'KR', 'KW', 'LI',
                'MO', 'MK', 'MY', 'ME', 'OM', 'PH', 'QA', 'SA', 'RS', 'ZA', 'LK', 'TW', 'TH', 'TR', 'VN'
            ])
            &&
            $weight > 0
            &&
            $weight <= 1
        ) {
            $this->accountCode = "US";
            return $this->dhlAccountUSA;
        } elseif (
            !empty($countryCode)
            &&
            in_array($countryCode, [
                'JP', 'AU', 'BH', 'GI', 'NZ', 'MY', 'MX', 'SG', 'AE', 'ID', 'TR', 'AL', 'BD',
                'BA', 'IC', 'CN', 'GG', 'HK', 'IN', 'ID', 'IL', 'JE', 'KR', 'KW', 'LI', 'MO',
                'MK', 'MY', 'ME', 'OM', 'PH', 'QA', 'SA', 'RS', 'ZA', 'LK', 'TW', 'TH', 'TR', 'VN'
            ])
            &&
            $weight > 0
            &&
            $weight <= 1.5
        ) {
            $this->accountCode = "US";
            return $this->dhlAccountUSA;
        } else {
            $this->accountCode = "UA";
            return $this->dhlAccount;
        }

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
        $return = false;
        $em = $this->getEm();

        $shipSumm = 0;
        $prArr = [];
        $declareCount = 0;
        $declareSum = 0;
        if (true
        ) {

            if ($object->getProducts()) {
                foreach ($object->getProducts() as $product) {
                    $declareSum = $declareSum + $product->getPrice();
                    $declareCount = $declareCount + $product->getCount();
                }
            }
        }


        $erType = 1;

        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><p:DCTRequest schemaVersion=\"2.0\" xsi:schemaLocation=\"http://www.dhl.com DCT-req.xsd\" xmlns:p=\"http://www.dhl.com\" xmlns:p1=\"http://www.dhl.com/datatypes\" xmlns:p2=\"http://www.dhl.com/DCTRequestdatatypes\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"></p:DCTRequest>");
        $GetQuote = $xml->addChild('GetQuote',
            null, "_");

        $Request = $GetQuote->addChild('Request',
            null, "_");

        $ServiceHeader = $Request->addChild("ServiceHeader");

        $ServiceHeader->addChild("MessageTime", date("c"));
        $ServiceHeader->addChild("MessageReference", substr("SkladUSA_dhl_to_rate_shipment_#" . $object->getId() . "_" . time() . "_" . time(), 0, 32));
        $ServiceHeader->addChild("SiteID", $this->dhlSiteId);
        $ServiceHeader->addChild("Password", $this->dhlSitePass);
        $MetaData = $Request->addChild("MetaData");
        $MetaData->addChild("SoftwareName", "3PV");
        $MetaData->addChild("SoftwareVersion", "6.2");

        $test=1;
        $From = $GetQuote->addChild("From", null, "_");
        if($test == 0){
            $From->addChild("CountryCode", "US");
            $From->addChild("Postalcode", $this->dhlFromZip);
            $From->addChild("City", $this->dhlfromCity)    ;
        }else{
            $From->addChild("CountryCode", "US");
            $From->addChild("Postalcode", "10001");
            $From->addChild("City", "New York");
        }





        $this->getAccountId($object);

        $BkgDetails = $GetQuote->addChild("BkgDetails", null, "_");
        $BkgDetails->addChild("PaymentCountryCode", $this->accountCode);
        $BkgDetails->addChild("Date", date('Y-m-d'));
        $BkgDetails->addChild("ReadyTime", 'PT10H21M');
        $BkgDetails->addChild("ReadyTimeGMTOffset", date('O'));
        $BkgDetails->addChild("DimensionUnit", 'CM');
        $BkgDetails->addChild("WeightUnit", 'KG');
        $Pieces = $BkgDetails->addChild("Pieces", null, "_");

        $x = 1;
        do {
            $Piece = $Pieces->addChild("Piece");
            $Piece->addChild("PieceID", 12);
            //$Piece->addChild("PackageType","YP");
            if ($test == 0) {
                $Piece->addChild("Height", $object->getSendDetailHeight());
                $Piece->addChild("Depth", $object->getSendDetailLength());
                $Piece->addChild("Width", $object->getSendDetailWidth());
                $Piece->addChild("Weight", number_format(($object->getSendDetailWeight()/100), 3, '.', ''));
            } else {
                $Piece->addChild("Height", 24);
                $Piece->addChild("Depth", 3);
                $Piece->addChild("Width", 14);
                $Piece->addChild("Weight", 1.500);

            }
        } while ($x++ < $object->getSendDetailPlaces());

        $BkgDetails->addChild("PaymentAccountNumber", $this->getAccountId($object));
        $BkgDetails->addChild("IsDutiable", 'Y');
        $QtdShp1 = $BkgDetails->addChild("QtdShp", null, "_");
        $QtdShp1->addChild("GlobalProductCode", 'P');
        /*
        $QtdShp2=$BkgDetails->addChild("QtdShp", null,"_");
        $BkgDetails->addChild("GlobalProductCode",'Y');
        */

        $To = $GetQuote->addChild("To", null, "_");

            if ($test==1){

                $To->addChild("CountryCode", "UA");
                $To->addChild("Postalcode", "01000");
                $To->addChild("City", "Kiev");
            }else{
                $To->addChild("CountryCode", "UA");
                $To->addChild("Postalcode", $object->getAddresses()->getZip());
                $To->addChild("City", $object->getAddresses()->getCity());
            }







        $Dutiable = $GetQuote->addChild("Dutiable", null, "_");
        $Dutiable->addChild("DeclaredCurrency", 'USD');
        $Dutiable->addChild("DeclaredValue", $declareSum);

        /* old code */

        $xml = $xml->asXML();

        $xml = str_replace('xmlns="_"', "", $xml);
        $xml = str_replace("\n", "", $xml);

        $ch = curl_init($this->dhlUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3000);
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        $http_headers = array(
            'Content-type: ' . 'text/xml'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $result = @curl_exec($ch);
        $movies = new \SimpleXMLElement($result);

        $GetQuoteResponse = $movies->GetQuoteResponse ?? false;
        $BkgDetails1 = ($GetQuoteResponse) ? $GetQuoteResponse->BkgDetails ?? false : false;
        $QtdShp1 = ($BkgDetails1) ? $BkgDetails1->QtdShp ?? false : false;
        $QtdSInAdCur = ($QtdShp1) ? $QtdShp1->QtdSInAdCur ?? false : false;
        if (
        $QtdSInAdCur
        ) {

            foreach ($QtdSInAdCur as $test) {
                if (isset($test) && isset($test->CurrencyCode) && (string)$test->CurrencyCode == 'USD') {
                    $shipSumm = (float)$test->TotalAmount;
                }
            }
        }

        $note = $GetQuoteResponse->Note ?? false;
        if ($note) {
            $status = (string)$note->ActionStatus ?? false;
            if ($status && $status == 'Failure') {
                $Condition = $note->Condition ?? false;
                if ($Condition->ConditionData)
                    $this->dhlErrors = (string)$Condition->ConditionData;
            }
        }

        if ($shipSumm == 0 && empty($this->dhlErrors)) {

            $Response = $movies->Response ?? false;
            $statusResponce = $Response->Status ?? false;

            $ActionStatus = (string)$statusResponce->ActionStatus ?? false;
            if ($ActionStatus && $ActionStatus == 'Error') {
                $Condition = $statusResponce->Condition ?? false;
                if ($Condition) {
                    $ConditionData = (string)$Condition->ConditionData ?? false;
                    if ($ConditionData) $this->dhlErrors = (string)$Condition->ConditionData;
                }
            }
        }
        return $shipSumm;
    }


    private function setContainer($c)
    {
        $this->container = $c;
    }
}