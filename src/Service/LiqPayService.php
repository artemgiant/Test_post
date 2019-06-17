<?php
namespace App\Service;


use App\Entity\TransactionLiqPay;
use App\Entity\Order;

use Doctrine\ORM\EntityManager;

use App\Helper\LiqPay;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\EntityManagerInterface;

define("LOG_LIQPAY", getcwd() . "/../liqPay.log");
/**
 * Class LiqPayService
 */
class LiqPayService
{

    private $liqpay_public_key = "i51022028690";
    private $liqpay_private_key = "EFQZ16fDWsmGLtA9Afea57LhmZN1MCDmbIbrDDvf";


 //   private $liqpay_public_key = "i49780947016";
 //   private $liqpay_private_key = "vzWMZHg2z2AQh2Eg7EYiI5YDiQHYQS7K1XoJbEap";

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $object Order
     *
     * @return array
     */
    public function LiqPayForm($summ,$description,$orderId)
    {
        $liqpay = new Liqpay($this->liqpay_public_key, $this->liqpay_private_key);
        $summ=round($summ+$summ*2.75/100,2);
        $buttonHtml = $liqpay->cnb_form(array(
            'action'         => 'pay',
            'amount'         => $summ,
            'currency'       => Liqpay::CURRENCY_USD,
            'description'    => $description,
            'order_id'       => $orderId,
            'version'        => '3',
            'result_url'     => 'https://expressposhta.com/payment/result',
            'server_url'     => 'https://expressposhta.com/payment/check'
           // 'sandbox'        => '1',
        ));

        return $buttonHtml;
    }

    /**
     *
     * @return mixed
     */
    public function check($post)
    {
        error_log('------START-----' . date('Y-m-d H:i') . PHP_EOL, 3, LOG_LIQPAY);
        error_log('#RESPONSE#' . PHP_EOL, 3, LOG_LIQPAY);
        error_log(print_r($post, true) . PHP_EOL, 3, LOG_LIQPAY);
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = json_decode($raw_post_data, true);

        error_log(print_r($raw_post_array, true) . PHP_EOL, 3, LOG_LIQPAY);
        if (!empty($post) && isset($post['data']) && isset($post['signature']))
        {
            $data=$post['data'];
            $signature=$post['signature'];
        }elseif(!empty($raw_post_array) && isset($raw_post_array['data']) && isset($raw_post_array['signature']))
        {
            $data=$raw_post_array['data'];
            $signature=$raw_post_array['signature'];
        }

       $resultSignature = base64_encode( sha1( $this->liqpay_private_key . $data . $this->liqpay_private_key, 1) );

        if($signature !== $resultSignature)
        {
            return 404;
        }

        $data = json_decode(base64_decode($data), 1);
        switch($data['status'])
        {
            case 'success':
            case 'sandbox':
            case 'wait_accept':
                $this->storePaymentData($data);
                break;
            default: //'error', 'failure', 'reversed', 'subscribed', 'unsubscribed
                //log
                return;

        }
        error_log("\n\n\n", 3, LOG_LIQPAY);
        error_log("----------END-----------", 3, LOG_LIQPAY);
        error_log("\n\n\n", 3, LOG_LIQPAY);

        return 'sucess';
    }

    private function storePaymentData($data) {
        error_log("----------SAVE-----------", 3, LOG_LIQPAY);
        error_log(print_r($data, true) . PHP_EOL, 3, LOG_LIQPAY);
        /* @var TransactionLiqPay $trLiqPay */
        $trLiqPay =$this->getEm()->getRepository(TransactionLiqPay::class)->findBy(['number'=>$data['order_id']]);
        if (empty($trLiqPay)) {
            $trLiqPay = new TransactionLiqPay();
            $trLiqPay->setCreatedAt(new \DateTime());
            $trLiqPay->setNumber($data['order_id']);
            $amount=$data['amount']??0;
            $receiverCommission=$data['receiver_commission']??0;
            $trLiqPay->setSum($amount-$receiverCommission);
           if (isset($data['sender_first_name'])) $trLiqPay->setFirstName($data['sender_first_name']);
           if (isset($data['sender_last_name'])) $trLiqPay->setLastName($data['sender_last_name']);
           if (isset($data['sender_phone'])) $trLiqPay->setPhoneNumber($data['sender_phone']);
            $trLiqPay->setLiqpayOrderId($data['liqpay_order_id']);
            $trLiqPay->setStatus($data['status']);
            $trLiqPay->setLiqpayInfo(json_encode($data));
            $this->getEm()->persist($trLiqPay);
            $this->getEm()->flush([$trLiqPay]);
            $arrTmp = explode("_", $data['order_id']);
            $userTmp=null;
            if ($arrTmp[0] == 'SKLAD' && count($arrTmp) > 2) {
                if (isset($arrTmp[1]) && !empty($arrTmp[1]) && strpos($arrTmp[1], 'UID') !== false) {
                    $userId = trim(str_replace("UID", "", $arrTmp[1]));
                    error_log("---------- USER ID -----------", 3, LOG_LIQPAY);
                    error_log($userId . PHP_EOL, 3, LOG_LIQPAY);
                    $userTmp=$this->getEm()->getRepository("AppBundle:User")->find((int)$userId);
                    if ($userTmp){
                        $this->container->get('app.balance')
                            ->addMoney($userTmp, $trLiqPay->getSum(),
                                'new payment from LiqPay added, id:' . $trLiqPay->getId(),null,$trLiqPay);
                    }
                }
            }
            if (!empty($userTmp)){
                $trLiqPay->setUser($userTmp);
            }

            $this->getEm()->persist($trLiqPay);
            $this->getEm()->flush([$trLiqPay]);
        }
    }

    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->em;
    }



}