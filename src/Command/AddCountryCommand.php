<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;

class AddCountryCommand extends Command
{
    protected static $defaultName = 'app:country:add';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }



    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $countries = array(
            "US"=>"USA",
            "CA"=>"Canada",
            "AF"=>"Afghanistan",
            "AX"=>"Aland Islands",
            "AL"=>"Albania",
            "DZ"=>"Algeria",
            "AD"=>"Andorra",
            "AO"=>"Angola",
            "AI"=>"Anguilla","AG"=>"Antigua and Barbuda","AR"=>"Argentina","AM"=>"Armenia","AW"=>"Aruba","AU"=>"Australia","AT"=>"Austria","AZ"=>"Azerbajan","BS"=>"Bahamas","BH"=>"Bahrain","BD"=>"Bangladesh","BB"=>"Barbados","BY"=>"Belarus","BE"=>"Belgium","BZ"=>"Belize","BJ"=>"Benin","BM"=>"Bermuda","BT"=>"Bhutan","BO"=>"Bolivia","XB"=>"Bonaire","BA"=>"Bosnia","BW"=>"Botswana","BR"=>"Brazil","VG"=>"British Virgin Islands","BN"=>"Brunei Darussalam","BG"=>"Bulgaria","BF"=>"Burkina Faso","BI"=>"Burundi","KH"=>"Cambodia","CM"=>"Cameroon","IC"=>"Canary Islands","CV"=>"Cape Verde","KY"=>"Cayman Islands","CF"=>"Central African Republic","TD"=>"Chad","JE"=>"Channel Islands","CL"=>"Chile","CN"=>"China","CX"=>"Christmas Islands","CC"=>"Cocos (Keeling) Islands","CO"=>"Colombia","KM"=>"Comoros","CG"=>"Congo - Republic of","CK"=>"Cook Islands","CR"=>"Costa Rica","HR"=>"Croatia","CU"=>"Cuba","CY"=>"Cyprus","CZ"=>"Czech Republic","DK"=>"Denmark","DJ"=>"Djibouti","DM"=>"Dominica","DO"=>"Dominican Republic","TP"=>"East Timor","EC"=>"Ecuador","EG"=>"Egypt","SV"=>"El Salvador","GQ"=>"Equatorial Guniea","ER"=>"Eritrea","EE"=>"Estonia","SZ"=>"eSwatini","ET"=>"Ethiopia","FK"=>"Falkland Islands","FO"=>"Faroe Islands (Denmark)","FJ"=>"Fiji","FI"=>"Finland","FR"=>"France","GF"=>"French Guiana","PF"=>"French Polynesia","GA"=>"Gabon","GM"=>"Gambia","GE"=>"Georgia","DE"=>"Germany","GH"=>"Ghana","GI"=>"Gibraltar","GR"=>"Greece","GL"=>"Greenland","GD"=>"Grenada","GP"=>"Guadeloupe","GT"=>"Guatemala","GG"=>"Guernsey","GN"=>"Guinea","GW"=>"Guinea-Bissau","GY"=>"Guyana","HT"=>"Haiti","HN"=>"Honduras","HK"=>"Hong Kong","HU"=>"Hungary","IS"=>"Iceland","IN"=>"India","ID"=>"Indonesia","IR"=>"Iran","IQ"=>"Iraq","IE"=>"Ireland","IM"=>"Isle of Man","IL"=>"Israel","IT"=>"Italy","JM"=>"Jamaica","JP"=>"Japan","JO"=>"Jordan","KZ"=>"Kazakhstan","KE"=>"Kenya","KI"=>"Kiribati","KR"=>"Korea (South Korea)","KV"=>"Kosovo","KW"=>"Kuwait","KG"=>"Kyrgyzstan","LA"=>"Laos","LV"=>"Latvia","LB"=>"Lebanon","LS"=>"Lesotho","LR"=>"Liberia","LI"=>"Liechtenstein","LT"=>"Lithuania","LU"=>"Luxembourg","MO"=>"Macau","MK"=>"Macedonia","MG"=>"Madagascar","MW"=>"Malawi","MY"=>"Malaysia","MV"=>"Maldives","ML"=>"Mali","MT"=>"Malta","MQ"=>"Martinique","MR"=>"Mauritania","MU"=>"Mauritius","YT"=>"Mayotte","MX"=>"Mexico","MD"=>"Moldova","MC"=>"Monaco","MN"=>"Mongolia","ME"=>"Montenegro","MS"=>"Montserrat","MA"=>"Morocco","MZ"=>"Mozambique","MM"=>"Myanmar","NA"=>"Namibia","NR"=>"Nauru","NP"=>"Nepal","NL"=>"Netherlands","NC"=>"New Caledonia","NZ"=>"New Zealand","NI"=>"Nicaragua","NE"=>"Niger","NG"=>"Nigeria","NU"=>"Niue","NF"=>"Norfolk Island","KP"=>"North Korea","NO"=>"Norway","OM"=>"Oman","PK"=>"Pakistan","PA"=>"Panama","PG"=>"Papua New Guinea","PY"=>"Paraguay","PE"=>"Peru","PH"=>"Philippines","PL"=>"Poland","PT"=>"Portugal","QA"=>"Qatar","RE"=>"Reunion","RO"=>"Romania","RU"=>"Russia","RW"=>"Rwanda","BQ"=>"Saba","LC"=>"Saint Lucia","WS"=>"Samoa","SM"=>"San Marino","SA"=>"Saudi Arabia","SN"=>"Senegal","RS"=>"Serbia","SC"=>"Seychelles","SL"=>"Sierra Leone","SG"=>"Singapore","SK"=>"Slovakia","SI"=>"Slovenia","SB"=>"Solomon Islands","ZA"=>"South Africa","GS"=>"South Georgia &amp; South Sandwich Is","ES"=>"Spain","LK"=>"Sri Lanka","KN"=>"St. Kitts and Nevis","MF"=>"St. Martin","VC"=>"St. Vincent and the Grenadines","XY"=>"St.Barthelemy","XE"=>"St.Eustatius","SH"=>"St.Helena","SD"=>"Sudan","SR"=>"Suriname","SJ"=>"Svalbard &amp; Jan Mayen","SE"=>"Sweden","CH"=>"Switzerland","SY"=>"Syria","TW"=>"Taiwan","TJ"=>"Tajikistan","TZ"=>"Tanzania","TH"=>"Thailand","TG"=>"Togo","TK"=>"Tokelau","TO"=>"Tonga","TT"=>"Trinidad and Tobago","TN"=>"Tunisia","TR"=>"Turkey","TM"=>"Turkmenistan","TC"=>"Turks and Caicos Islands","TV"=>"Tuvalu","UG"=>"Uganda","UA"=>"Ukraine","AE"=>"United Arab Emirates","GB"=>"United Kingdom","UY"=>"Uruguay","UZ"=>"Uzbekistan","VU"=>"Vanuatu","VE"=>"Venezuela","VN"=>"Vietnam","YE"=>"Yemen","ZM"=>"Zambia","ZW"=>"Zimbabwe","AS"=>"American Samoa","_02"=>"Canal Zone","GU"=>"Guam","MH"=>"Marshall Islands","FM"=>"Micronesia","NP2"=>"Northern Mariana Islands","PW"=>"Palau","PR"=>"Puerto Rico","MP"=>"Saipan","VI"=>"U.S. Virgin Islands"
        );

        Foreach($countries as $code=>$name){
            $country=$this->entityManager
                ->getRepository(Country::class)
                ->findOneBy(['shortName'=>$code]);
            if (empty($country)) {
                $country= new Country();
                $country->setName(trim($name))
                    ->setShortName($code);
                $this->entityManager->persist($country);
                $output->writeln(sprintf('Added country <comment>%s</comment>', $name));
            }
        }
        $this->entityManager->flush();


    }


}
