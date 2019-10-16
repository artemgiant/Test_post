<?php

namespace  App\Service;

use App\Entity\Invoices;
use App\Entity\TransactionLiqPay;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
define("AUTHORIZE_ERROR_LOG", getcwd() . "/../authorizeErrors.log");

class AuthorizeDotNetService
{
    const API_TARGET_URL = 'https://api.authorize.net/xml/v1/request.api';
    const TARGET_URL = 'https://test.authorize.net/payment/payment';
    const DELIMITER = '_';
    private $requestStack;

    private $auth;
    private $user;

    private $transaction;
    private $settings;
    private $baseUrl;
    private $refId;
        /** @var EntityManagerInterface $em */
    private $em;
    private $route;
    private $twig;

    private $procent=3;
    private $addition=0.3;

    private $id     = '6Zu4b25V4';
    //private $key    = '6QQJ346P98feERc3';
    //private $key    = '9pa6W7p9EEW34xQy';
    private $key    = '269AH2bvQ4Bm5pZ6';
    //test
//        const API_TARGET_URL = 'https://apitest.authorize.net/xml/v1/request.api';
//        private $id     = '2afUTk8Qx4a';//'6Zu4b25V4';
//        private $key    = '637TSNCnqd77nw7P';//'6QQJ346P98feERc3';

    // used to show status string in template
    private static $avsResultCodeStatuses = [
        'A' => 'The street address matched, but the postal code did not.',
        'B' => 'No address information was provided.',
        'E' => 'The AVS check returned an error.',
        'G' => 'The card was issued by a bank outside the U.S. and does not support AVS.',
        'N' => 'Neither the street address nor postal code matched.',
        'P' => 'AVS is not applicable for this transaction.',
        'R' => 'Retry — AVS was unavailable or timed out.',
        'S' => 'AVS is not supported by card issuer.',
        'U' => 'Address information is unavailable.',
        'W' => 'The US ZIP+4 code matches, but the street address does not.',
        'X' => 'Both the street address and the US ZIP+4 code matched.',
        'Y' => 'The street address and postal code matched.',
        'Z' => 'The postal code matched, but the street address did not.',
    ];

    // used to show status string in template
    private static $cvvResultCodeStatuses = [
        'M' => 'CVV matched.',
        'N' => 'CVV did not match.',
        'P' => 'CVV was not processed.',
        'S' => 'CVV should have been present but was not indicated.',
        'U' => 'The issuer was unable to process the CVV check.',
    ];


    /**
     * AuthorizeDotNetService constructor.
     * @param RequestStack $requestStack
     */
    public function __construct( EntityManagerInterface $em,RequestStack $requestStack,TokenStorageInterface $tokenStorage,UrlGeneratorInterface $router,Environment $twig)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->route=$router;
        $this->twig = $twig;

        $this->setAuth($this->id, $this->key);
        $this->user = ($tokenStorage->getToken())?$tokenStorage->getToken()->getUser():null;
        $request = $this->baseUrl = $this->requestStack->getCurrentRequest();
        $this->setBaseUrlFromRequest($request);
    }

    /**
     * @param $id
     * @param $key
     * @return $this
     */
    private function setAuth($id, $key) {
        $this->auth = [
            "name"              => $id,
            "transactionKey"    => $key
        ];

        return $this;
    }

    /**
     *
     */
    public function validateAuth() {
        $data = [];

        return $this->sendRequest('authenticateTestRequest', $data);
    }

    /**
     * @param integer $id
     * @return $this
     */
    public function setInvoiceId($id) {
        $this->refId = $id;
        return $this;
    }

    /**
     * @param $amount
     * @param string $customerEmail
     * @param string $customerProfileId
     * @return $this
     */
    public function setTransaction($amount, $customerEmail = '', $customerProfileId = '') {

        if(empty($amount))
            throw new \InvalidArgumentException('amount is empty');

        $amount = number_format($amount, 2, '.', '');

        $this->transaction = [
            "transactionType"   => "authCaptureTransaction",
			"amount"            => $amount,
        ];

        if(!empty($customerProfileId)) {
            $this->transaction["profile"] = [
                "customerProfileId" => $customerProfileId
            ];
        }

        if(!empty($customerEmail)) {
            $this->transaction["customer"] = [
                "email" => $customerEmail
            ];
        }

        if(!empty($billingInfo)) {
            $this->transaction["billTo"] = [
                "firstName" => "Ellen",
                "lastName" => "Johnson",
                "company" => "Souveniropolis",
                "address" => "14 Main Street",
                "city" => "Pecan Springs",
                "state" => "TX",
                "zip" => "44628",
                "country" => "USA"
            ];
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function setPaymentSettings() {
//        "{\"showReceipt\": true, \"url\": \"https://mysite.com/receipt\", \"urlText\": \"Continue\", \"cancelUrl\": \"https://mysite.com/cancel\", \"cancelUrlText\": \"Cancel\"}"
        $this->settings = [
            "setting" => [
                [
                    "settingName" => "hostedPaymentReturnOptions",
                    "settingValue" => json_encode([
                        "showReceipt"   => false,
                        "url"           => $this->baseUrl . $this->route->generate('post_dashboard'),
                        "urlText"       => "Continue",
                        "cancelUrl"     => $this->baseUrl . $this->route->generate('post_dashboard'),
                        "cancelUrlText" => "Cancel"
                    ])
    			],
                [
                    "settingName" => "hostedPaymentButtonOptions",
                    "settingValue" => "{\"text\": \"Pay by credit card\"}"
                ],
                [
                    "settingName" => "hostedPaymentStyleOptions",
                    "settingValue" => "{\"bgColor\": \"blue\"}"
                ],
                [
                    "settingName" => "hostedPaymentPaymentOptions",
                    "settingValue" => "{\"cardCodeRequired\": true, \"showCreditCard\": true, \"showBankAccount\": false}"
                ],
                [
                    "settingName" => "hostedPaymentSecurityOptions",
                    "settingValue" => "{\"captcha\": false}"
                ],
                [
                    "settingName" => "hostedPaymentShippingAddressOptions",
                    "settingValue" => "{\"show\": false, \"required\": false}"
                ],
                [
                    "settingName" => "hostedPaymentBillingAddressOptions",
				    "settingValue" => "{\"show\": true, \"required\": false}"
                ],
                [
                    "settingName" => "hostedPaymentCustomerOptions",
                    "settingValue" => "{\"showEmail\": false, \"requiredEmail\": false, \"addPaymentProfile\": true}"
                ],
                [
                    "settingName" => "hostedPaymentOrderOptions",
                    "settingValue" => "{\"show\": true, \"merchantName\": \"Skladusa\"}"
                ],
                [
                    "settingName" => "hostedPaymentIFrameCommunicatorUrl",
                    "settingValue" => "{\"url\": \"{$this->baseUrl}{$this->route->generate('auth_iframe_communicator')}\"}"
//                    "settingValue" => "{\"url\": \"{$this->baseUrl}{$this->container->get('router')->generate('auth_inner_popup')}\"}"
                ]
           ]
        ];

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken() {
        $data = [
            'transactionRequest'        => $this->transaction,
            'hostedPaymentSettings'     => $this->settings
        ];

        $response = $this->sendRequest('getHostedPaymentPageRequest', $data);

        return $this->processTokenCallback($response);
    }

    protected function sendRequest($key, $data) {

        $data = [
            $key => array_merge(
                [
                    'merchantAuthentication' => $this->auth
                ],
                !empty($this->refId) ? ['refId' => $this->refId] : [],
                $data
            )
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL             => self::API_TARGET_URL,
            CURLOPT_POST            => true,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => [
                "Content-Type: application/json",
                "Accept: application/json",
                "Accept-Encoding: gzip",
            ],
            CURLOPT_POSTFIELDS      => json_encode($data),
            CURLOPT_USERAGENT       => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_SSL_VERIFYPEER  => 0
        ]);

        $response = curl_exec($curl);

        if(curl_errno($curl)) {
            throw new \InvalidArgumentException(curl_error($curl));
        }

        curl_close($curl);

        $response = $this->decode($response);

        if( !isset($response['messages']) )
            throw new \InvalidArgumentException('messages is empty');

        if( !isset($response['messages']['resultCode']) )
            throw new \InvalidArgumentException('resultCode is empty');

//        if( $response['messages']['resultCode'] != 'Ok' )
//            throw new \InvalidArgumentException('resultCode is not Ok');

        return $response;
    }

    /**
     * @param $request
     * @return $this
     */
    private function setBaseUrlFromRequest($request = null) {
        if(!$request) {
            $this->baseUrl ='https://system.expressposhta.com';
        } else {
            $s = $request->server->get('SERVER_NAME') ? 's' : '';
            $this->baseUrl = 'http' . $s . '://' . $request->server->get('SERVER_NAME');
        }

        return $this;
    }

    /**
     * @param $response
     * @return mixed
     *
     * @throws  \InvalidArgumentException
     */
    public function processTokenCallback($response) {

        if( !isset($response['token']) ) {

        error_log('token is empty --' . date('Y-m-d H:i') . " | " . print_r($response,1) . PHP_EOL, 3, AUTHORIZE_ERROR_LOG);
            throw new \InvalidArgumentException('token is empty');
        }

        return $response['token'];
    }

    public function pre($array = ['Empty!']) {
        echo '<pre>' . print_r($array, 1) . '</pre>';
    }

    public function decode($response) {

        if(strpos($response, '{') !== 0) { //somehow we have 3 bad symbols at the beginning of the string
            $response = substr($response, 3);
        }

        $result = json_decode($response, true);

        return $result;
    }

    public function getPayPalLink($amount) {

        if(empty($amount))
            throw new \InvalidArgumentException('amount is empty');

        $amount = number_format($amount, 2, '.', '');

        $data = [
            "transactionRequest" => [
                "transactionType" => "authOnlyTransaction",
                "amount" => $amount,
                "payment" => [
                    "payPal" => [
                        "successUrl"    => $this->baseUrl . "/success.html",
                        "cancelUrl"     => $this->baseUrl . "/cancel.html"
                    ]
                ]
            ]
        ];

        $response = $this->sendRequest('createTransactionRequest', $data);

        return $this->processPayPalLinkCallback($response);
    }

    public function processPayPalLinkCallback($response) {

        $this->pre($response);

        if( !isset($response['transactionResponse']) )
            throw new \InvalidArgumentException('transactionResponse is empty');

        if( !isset($response['transactionResponse']['secureAcceptance']) )
            throw new \InvalidArgumentException('secureAcceptance is empty');

        if( !isset($response['transactionResponse']['SecureAcceptanceUrl']) )
            throw new \InvalidArgumentException('SecureAcceptanceUrl is empty');

        return $response['transactionResponse']['SecureAcceptanceUrl'];
    }

    /**
     * @return mixed
     */
    public function getVisaCheckoutLink()
    {
        return $this->auth;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getTransactionDetails($id) {

        $data = [
            'transId' => $id
        ];

        return $this->sendRequest('getTransactionDetailsRequest', $data);
    }


    /**
     * @param $response
     */
    public function processCustomerWebHookCallback($response) {
        //
    }

    /**
     * @param $response
     */
    public function processCustomerSubscriptionWebHookCallback($response) {
        //
    }

    /**
     * @param Invoices $invoice
     * @return $this
     */

    public function setInvoice($invoice) {

        if(empty($invoice->getAmount()))
            throw new \LogicException('invoice\'s amount is empty');

        if(empty($invoice->getId()))
            throw new \LogicException('invoice\'s id is empty');

        $this
            ->setTransaction($invoice->getAmount())
            ->setInvoiceId($invoice->getId())
            ->setPaymentSettings()
        ;

        return $this;
    }





    public function storePaymentData() {
        $raw_post_data = file_get_contents('php://input');

        $data = json_decode($raw_post_data, true);
        error_log("----------SAVE-----------", 3, AUTHORIZE_ERROR_LOG);
        error_log(print_r($data, true) . PHP_EOL, 3, AUTHORIZE_ERROR_LOG);
        /* @var TransactionLiqPay $trAutorize */
        $trAutorize =$this->getEm()->getRepository(TransactionLiqPay::class)->findOneBy(['number'=>$data['transaction']['transId']]);

        if (empty($trAutorize)) {
            /** @var TransactionLiqPay $trAutorize */
            $trAutorize = new TransactionLiqPay();
            $trAutorize->setCreatedAt(new \DateTime());
            $trAutorize->setNumber($data['transaction']['transId']);
            $amount=$data['transaction']['authAmount']??0;
            $receiverCommission=$data['receiver_commission']??0;
            $trAutorize->setSum($amount-$receiverCommission);
            $billTo = $data['transaction']['billTo'];


            $trAutorize
                ->setFirstName($billTo['firstName'] ?? '')
                ->setLastName($billTo['lastName'] ?? '')
                ->setPhoneNumber($billTo['phoneNumber'] ?? '')
           ;
            $trAutorize->setLiqpayOrderId('');
            $trAutorize->setStatus('success');
            $trAutorize->setLiqpayInfo(json_encode($data));
            $this->getEm()->persist($trAutorize);
            $this->getEm()->flush([$trAutorize]);
            $arrTmp = explode("_", $data['transrefId']);
            $userTmp=null;

            if ($arrTmp[0] == 'EXPRESSINVOICE' && count($arrTmp) >= 2) {

                if (isset($arrTmp[1]) && !empty($arrTmp[1])) {
                    $orderId = (int)$arrTmp[1];

                    error_log("---------- USER ID -----------", 3, AUTHORIZE_ERROR_LOG);
                    error_log($orderId . PHP_EOL, 3, AUTHORIZE_ERROR_LOG);
                    /* @var $order Order */
                    $invoice = $this->getEm()->getRepository(Invoices::class)->find($orderId);
                    /* @var $invoice Invoices */
                    $order = ($invoice && $invoice->getOrderId())?$invoice->getOrderId():false;

                    if ($order) {
                        $trAutorize->setUser($order->getUser());
                        $trAutorize->setInvoice($invoice);
                        $invoice->setIsPaid(true);
//                        $invoice->setTrNum("EP".($trLiqPay->getId()+57354658)."UA"); // this method is not implemented for invoices yet. uncomment when ready.
                        $this->getEm()->persist($trAutorize);
                        $this->getEm()->flush();

                        $orderInvoices = $this->getEm()->getRepository(Invoices::class)->findBy(['orderId'=>$order->getId()]);
                        $orderStatus = $this->getEm()->getRepository(OrderStatus::class)->findOneBy(['status'=>'paid']);
                        // foreach ($orderInvoices as $orderInvoice) {
                        //     if (!$orderInvoice->isPaid()) {
                        //        $orderStatus = $this->getEm()->getRepository(OrderStatus::class)->findOneBy(['status'=>'new']);
                        //      }
                        //   }
                        $order->setOrderStatus($orderStatus);
                        if (is_null($order->getTrNum())||(trim($order->getTrNum()) == '')) {
                            $order->setTrNum("EP".($order->getId()+57354658)."UA"); // this number supposed to be attached to the paid invoice
                        }
                        $this->getEm()->persist($order);
                    }
                }
            }
            $this->getEm()->persist($trAutorize);
            $this->getEm()->flush();
        }
    }

    /**
     * @param $transactionResponse
     */
    protected function processInternalTransaction($transactionResponse) {

        $invoiceId = $transactionResponse['transrefId'];



        /** @var AuthorizeDotNetInvoice $invoice */
        $invoice = $this->em->getRepository('AppBundle:AuthorizeDotNetInvoice')->find($invoiceId);

        if( !$invoice ) {
            throw new \LogicException('invoice not found');
        }

        $invoice->setIsPayed(true);


        $this->em->persist($invoice);

        $oldTransaction = $this->getTransactionByNumber($transactionResponse['transaction']['transId'] ?? '');

        $transaction = $oldTransaction ?? new Transaction();
        $transaction
            ->setTransactionDate($transactionResponse['transaction']['submitTimeUTC'] ?? '')
            ->setTrackingNumber('')
            ->setNumber($transactionResponse['transaction']['transId'] ?? '')
            ->setUser($invoice->getUser())
            ->setName('Authorize Internal')
            ->setTotalSum($transactionResponse['transaction']['authAmount'])
            ->setInterimSum($transactionResponse['transaction']['authAmount'])
            ->setType(Transaction::TYPE_AUTHORIZE_DOT_NET)
            ->setPaymentStatus(Transaction::PAYMENT_STATUS_PENDING)
            ->setCustomerIp($transactionResponse['transaction']['customerIP'] ?? '')
            ->setAddressVerificationStatus($transactionResponse['transaction']['AVSResponse'] ?? '')
            ->setCardCodeStatus($transactionResponse['transaction']['cardCodeResponse'] ?? '')
            ->setFraudAction($transactionResponse['transaction']['FDSFilterAction'] ?? '')
            ->setFraudFilters(implode(', ', array_map(function($item) {
                return $item['name'];
            }, $transactionResponse['transaction']['FDSFilters'] ?? [])))
        ;

        if($listing = $invoice->getListing()) {
            $listing->setStatus(true);
            $this->em->persist($listing);
            $transaction
                ->setListing($listing)
            ;
        }

        $this->processBillToDataToTransactionFromResponse($transaction, $transactionResponse);

        if( isset($transactionResponse['transaction']['customer']) )
            $transaction->setPayedUserEmail($transactionResponse['transaction']['customer']['email'] ?? '');

        $this->em->persist($transaction);

        $this->em->flush();

        // $transactionResponse['transaction']['responseCode']
        // 1 - success
        // 4 - fraud
        $transactionResponseCode = $transactionResponse['transaction']['responseCode'] ?? 0;

        $transferMoney = ($transactionResponseCode == 1);

        $this->processTransactionCommissionsAndUserBalance($transaction, $invoice, $transferMoney);
    }

    /**
     * @param Listing $listing
     * @return AuthorizeDotNetInvoice
     */
    public function makeAuthorizeDotNetInvoiceFromListing($listing) {
        return $this->makeAuthorizeDotNetInvoice(
            $listing->getPrice(),
            $listing->getProductName(),
            $listing->getUser()
        );
    }

    /**
     * @param Transaction $transaction
     * @param $transactionResponse
     * @return Transaction $transaction
     */
    public function processBillToDataToTransactionFromResponse($transaction, $transactionResponse = array()) {

        if( !isset($transactionResponse['transaction']) )
            throw new \InvalidArgumentException('transaction is empty');

        if( !isset($transactionResponse['transaction']['billTo']) )
            return $transaction;

        $billTo = $transactionResponse['transaction']['billTo'];

        $transaction
            ->setUserPhone($billTo['phoneNumber'] ?? '')
            ->setPayedUserName(($billTo['firstName'] ?? '') . ' ' . ($billTo['lastName'] ?? '') . ' ' . ((isset($billTo['company']) && !empty(trim($billTo['company']))) ? '(' . trim($billTo['company']) . ')' : ''))
            ->setAddressPart($billTo['address'] ?? '')
            ->setCity($billTo['city'] ?? '')
            ->setUserState($billTo['state'] ?? '')
            ->setZip($billTo['zip'] ?? '')
            ->setCountry($billTo['country'] ?? '')
        ;

        return $transaction;
    }

    /**
     * @param $amount
     * @param $cardNumber
     * @param $cardCode
     * @param $cardExpirationDate
     * @param $billTo
     * @param $email
     * @param Listing $listing
     * @return mixed
     */
    public function createCardTransactionRequest($amount, $cardNumber, $cardCode, $cardExpirationDate, $billTo, $email = '', Listing $listing = null) {

        $data = [
            "transactionRequest" => [
                "transactionType" => "authCaptureTransaction",
                "amount" => $amount,
                "payment" => [
                    'creditCard' => [
                        'cardNumber'        => $cardNumber,
                        'expirationDate'    => $cardExpirationDate,
                        'cardCode'          => $cardCode,
                    ]
                ],
            ]
        ];

        if( $listing ) {
            $data['transactionRequest']['order'] = [
                'invoiceNumber' => $listing->getUrl(), // 1008, 1009 etc.
                'description'   => $listing->getProductName()
            ];
        } else {
            $data['transactionRequest']['order'] = [
                'invoiceNumber' => 'Donate',
                'description'   => 'Donate',
            ];
        }

        if( $email ) {
            $data['transactionRequest']['customer'] = [
                'type'  => 'individual',
                'id'    => time(), // unique, but not registered
                'email' => $email, // but this is the only way to send user's email in request
            ];
        }

        $data['transactionRequest']['billTo'] = $billTo;

        if( isset($billTo['phoneNumber']) )
            unset($billTo['phoneNumber']);

        if( isset($billTo['faxNumber']) )
            unset($billTo['faxNumber']);

        $data['transactionRequest']['shipTo'] = $billTo;

        if( $this->requestStack->getCurrentRequest() ) {
            $data['transactionRequest']['customerIP'] = $this->requestStack->getCurrentRequest()->getClientIp();
        }

        $response = $this->sendRequest('createTransactionRequest', $data);

//        $this->pre($response);

        return $this->processCardTransactionRequest($response);
    }

    public function processCardTransactionRequest($response) {

        // transaction will be added in notification callback

        if( isset($response['transactionResponse']) ) {
            if( isset ($response['transactionResponse']['errors']))
                throw new \LogicException($response['transactionResponse']['errors'][0]['errorText']);
        }

        if( $response['messages']['resultCode'] != 'Ok' )
            throw new \LogicException($response['messages']['message'][0]['text']);

        return true;
    }

    /**
     * @param $amount
     * @param $accountType
     * @param $routingNumber
     * @param $accountNumber
     * @param $nameOnAccount
     * @param $echeckType
     * @param $bankName
     * @return bool
     */
    public function createAccountTransactionRequest($amount, $accountType, $routingNumber, $accountNumber,
        $nameOnAccount, $echeckType, $bankName) {

        $data = [
            "transactionRequest" => [
                "transactionType" => "authCaptureTransaction",
                "amount" => $amount,
                "payment" => [
                    'bankAccount' => [
                        'accountType'   => $accountType,
                        'routingNumber' => $routingNumber,
                        'accountNumber' => $accountNumber,
                        'nameOnAccount' => $nameOnAccount,
                        'echeckType'    => $echeckType,
                        'bankName'      => $bankName,
//                        'checkNumber'   => '',
                    ]
                ]
            ]
        ];

        $response = $this->sendRequest('createTransactionRequest', $data);

        //        $this->pre($response);

        return $this->processAccountTransactionRequest($response);
    }

    public function processAccountTransactionRequest($response) {

        // transaction will be added in notification callback

        if( isset($response['transactionResponse']) ) {
            if( isset ($response['transactionResponse']['errors']))
                throw new \LogicException($response['transactionResponse']['errors'][0]['errorText']);
        }

        if( $response['messages']['resultCode'] != 'Ok' )
            throw new \LogicException($response['messages']['message'][0]['text']);

        return true;
    }

    public function getDonateInvoice() {


        $user = $this->em->getRepository('AppBundle:User')->findOneBy([
            'username' => 'donate@skladusa.com'
        ]);

        if( !$user ) {
            return false;
        }

        /** @var AuthorizeDotNetInvoice $donateInvoice */
        $donateInvoice = $this->em->getRepository('AppBundle:AuthorizeDotNetInvoice')->findOneBy([
            'user' => $user
        ]);

        return $donateInvoice;
    }

    /**
     * First - fill transaction - will use only it's fields values
     *
     * @param Transaction $transaction
     * @param AuthorizeDotNetInvoice $invoice
     * @param boolean $transferMoney
     */
    public function processTransactionCommissionsAndUserBalance($transaction, $invoice = null, $transferMoney = false) {



        $user = $transaction->getUser();

        $userBonus = 0.;

        $authorizeRate = $this->isUsa($transaction->getCountry()) ? 2.0 : 3.0;

        $authorizeCommission = round($transaction->getTotalSum() * $authorizeRate / 100, 2);
        /** @var Transaction  $transaction*/

        $authorizePerTransactionFee = Transaction::PER_TRANSACTION_FEE;
        $transaction
            ->setInterimSum($transaction->getInterimSum() - $authorizeCommission-$authorizePerTransactionFee)
            ->setCommission($authorizeCommission)
            ->setPerTransactionFee($authorizePerTransactionFee)
        ;

        $interimSum = $transaction->getInterimSum(); // without Authorize commission

        $donateInvoice = $this->getDonateInvoice();

        if( ($invoice && $donateInvoice && $invoice->getId() == $donateInvoice->getId()) || ($invoice && $invoice->isNoComission())) { // donate

            $systemCommission = 0.;

        } else {

            if ($user->getInterestRate() != -1) { // only system commission but no bonuses

                $interestRate = $user->getInterestRate();

                $systemCommission = round($interimSum * $interestRate / 100, 2);

            } else { // add standard commission and bonuses

                /**  @var Settings $settings */
                $settings = $this->em->getRepository('AppBundle:Settings')->find(1);

                $interestRate = $settings->getPersentToSystem(); // commission to system

                $systemCommission = round($interimSum * $interestRate / 100, 2);

                $bonusRate = $settings->getPersentToUser();

                $userBonus = round($interimSum * $bonusRate / 100, 2);
            }
        }

        $this->container->get('feltedin_app_service')->addToPTSBalance($systemCommission);

        $transaction
            ->setSystemsDeduction($systemCommission + $userBonus)   // big commission
            ->setDeductionForSystem($systemCommission)              // small commission
            ->setDeductionForUserBonus($userBonus)                  // user bonus
            ->setSum( round($interimSum - ($systemCommission + $userBonus), 2) )
        ;

        if( $transferMoney ) {

            $balanceService
                ->addMoney(
                    $user,
                    $transaction->getSum(),
                    'new transaction added, id:' . $transaction->getId(),
                    null,
                    $transaction
                );

            /* @var $transaction Transaction */

            if ($transaction->getSum() > 0 && $transaction->getDeductionForUserBonus() > 0) {
                $balanceService
                    ->addBonusMoney(
                        $user,
                        $transaction->getDeductionForUserBonus(),
                        'new transaction added, id:' . $transaction->getId(),
                        null,
                        $transaction);
            }

            $transaction->setPaymentStatus(Transaction::PAYMENT_STATUS_COMPLETED);
        }

        $this->em->persist($transaction);
        $this->em->persist($user);

        $this->em->flush();
    }

    private function isUsa($country) {
        return preg_match('/(^(US|Uni.*S|)|(США|Шта))/', $country);
    }

    public function getAVSString($key) {
        $string = '';

        if(trim($key)) {
            $string = self::$avsResultCodeStatuses[$key] ?? '';
        }

        return $string;
    }

    public function getCCSString($key) {
        $string = '';

        if(trim($key)) {
            $string = self::$cvvResultCodeStatuses[$key] ?? '';
        }

        return $string;
    }

    private function getTransactionByNumber($number) {

        $oldTransaction = null;

        if($number) {

            $oldTransaction = $this
                ->container
                ->get('doctrine')
                ->getManager()
                ->getRepository(Transaction::class)
                ->findOneBy([
                    'number' => $number
                ]);
        }

        return $oldTransaction;
    }

    public function makeAuthorizeDotNetInvoice($amount, $title,$invoiceId, $user,$noComission=false) {

        /** @var Invoices $invoice */
        $invoice=$this->em->getRepository(Invoices::class)
            ->find($invoiceId);
       if ($invoice ) {

           /** @var AuthorizeDotNetService $authDotNetService */
           $token = $this
               ->setTransaction($invoice->getPrice())
               ->setInvoiceId('EXPRESSINVOICE_' . $invoiceId)
               ->setPaymentSettings()
               ->getToken();

           $invoice->setFormToken($token);

           $this->em->persist($invoice);

           $this->em->flush();

           return $invoice;
       }
       elseif (!empty($invoice) && !empty( $invoice->getFormToken())) {
           return $invoice;
       }
       else return null;
    }

    /**
     * @param  $summ float
     * @param  $orderId string
     * @return mixed
     */
    public function getPaymentForm($summ,$orderId) {

        try {

            if(
                !$summ
            ) {
                throw new \Exception('Not valid data');
            }

            $title = "Expressposhta  pay from user #".$this->user->getId();

            $datasum=$summ + ($summ * $this->procent) / 100 + $this->addition;
            $invoice = $this->makeAuthorizeDotNetInvoice($summ, $title,$orderId, null,true);

            $token =($invoice)?$invoice->getFormToken():null;

            $view = $this->twig->render('authorize_dot_net/popupOuter.html.twig', [
//                'buttonTitle'   => 'Pay!',
                'orderId'      =>$orderId,
                'token'         => $token
            ]);



        } catch (\Exception $e) {
            $view = $e->getMessage();
        }

        return $view;
    }

    /**
     * @param  $summ float
     * @param  $orderId string
     * @return mixed
     */
    public function getTokenToForm($summ,$orderId) {
        $token=null;
        try {

            if(
            !$summ
            ) {
                throw new \Exception('Not valid data');
            }

            $title = "Expressposhta  pay from user #".$this->user->getId();


            $invoice = $this->makeAuthorizeDotNetInvoice($summ, $title,$orderId, null,true);

            $token =($invoice)?$invoice->getFormToken():null;

        } catch (\Exception $e) {
            $token = $e->getMessage();
        }

        return $token;
    }

    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->em;
    }

}
?>