<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Order
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Order
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="OrderProducts", mappedBy="orderId", cascade={"persist", "remove"})
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="Invoices", mappedBy="orderId", cascade={"persist", "remove"})
     */
    private $invoices;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $user;


    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Address",inversedBy="order")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $addresses;



    /**
     * @var string
     *
     * @ORM\Column(name="tracking_number", type="string", length=255, nullable=true)
     */
    private $trackingNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="volume_weigth", type="string", length=255, nullable=true)
     */
    private $volumeWeigth;

    /**
     * @var string
     *
     * @ORM\Column(name="declareValue", type="string", length=255, nullable=true)
     */
    private $declareValue;

    /**
     * @var string
     *
     * @ORM\Column(name="send_from_address", type="string", length=512, nullable=true)
     */
    private $sendFromAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="send_from_index", type="string", length=15, nullable=true)
     */
    private $sendFromIndex;

    /**
     * @var string
     *
     * @ORM\Column(name="send_from_city", type="string", length=255, nullable=true)
     */
    private $sendFromCity;

    /**
     * @var string
     *
     * @ORM\Column(name="send_from_phone", type="string", length=255, nullable=true)
     */
    private $sendFromPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="send_from_email", type="string", length=255, nullable=true)
     */
    private $sendFromEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="send_detail_places", type="integer", nullable=true)
     */
    private $sendDetailPlaces;

    /**
     * @var string
     *
     * @ORM\Column(name="send_detail_weight", type="float", nullable=true)
     */
    private $sendDetailWeight;

    /**
     * @var string
     *
     * @ORM\Column(name="send_detail_length", type="float", nullable=true)
     */
    private $sendDetailLength;

    /**
     * @var string
     *
     * @ORM\Column(name="send_detail_width", type="float", nullable=true)
     */
    private $sendDetailWidth;

    /**
     * @var string
     *
     * @ORM\Column(name="send_detail_height", type="float", nullable=true)
     */
    private $sendDetailHeight;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var OrderStatus
     * @ORM\ManyToOne(targetEntity="OrderStatus")
     * @ORM\JoinColumn(name="order_status", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $orderStatus;

    /**
     * @var float
     *
     * @ORM\Column(name="shipping_costs", type="float", nullable=true )
     */
    private $shippingCosts;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_status", type="string", length=255, nullable=true)
     */
    public $deliveryStatus;

    /**
     * @return int
     */
    public function getShippingCosts()
    {
        return $this->shippingCosts;
    }

    /**
     * @param int $shippingCosts
     */
    public function setShippingCosts($shippingCosts)
    {
        $this->shippingCosts = $shippingCosts;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ship_date", type="datetime", nullable=true)
     */
    private $shipDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=256, nullable=true)
     */
    public $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=256, nullable=true)
     */
    public $country;

    /**
     * @var string
     *
     * @ORM\Column(name="from_country", type="string", length=256, nullable=true)
     */
    public $fromCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=256, nullable=true)
     */
    public $city;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=256, nullable=true)
     */
    public $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="towarehouse", type="string", length=255, nullable=true)
     */
    public $towarehouse;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    public $quantity;

    public function __construct() {
        $this->quantity = 0;
        $this->createdAt = new \DateTime();
        $this->shipDate = new \DateTime();
        $this->adminCreate = false;
        $this->products = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString() {
        if($this->getUser()) {
            return '#' . $this->getId() . " : " . $this->getCustom();
        } else {
            return 'New orders';
        }
    }

    /**
     * @return mixed
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param mixed $addresses
     * @return Order
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }
    /**
     * Set trackingNumber
     *
     * @param string $trackingNumber
     * @return Order
     */
    public function setTrackingNumber($trackingNumber)
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }

    /**
     * Get trackingNumber
     *
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }

    /**
     * Set shipDate
     *
     * @param \DateTime $shipDate
     * @return Order
     */
    public function setShipDate($shipDate)
    {
        $this->shipDate = $shipDate;

        return $this;
    }

    /**
     * Get shipDate
     *
     * @return \DateTime
     */
    public function getShipDate()
    {
        return $this->shipDate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Order
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public $createdAtStr;
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAtStr()
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }


    /**
     * Set user
     *
     * @param User $user
     * @return Invoicing
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Add product
     *
     * @param OrderProducts $product
     * @return Order
     */
    public function addProduct(OrderProducts $product)
    {
        if ( !$product->getOrderId() instanceof Order ) {
            $product->setOrderId($this);
        }

        if( !$this->products->contains($product))
        {
            $this->products->add($product);
        }
        return $this;
    }

    /**
     * Remove product
     *
     * @param OrderProducts $product
     */
    public function removeProduct(OrderProducts $product)
    {
        if ($product instanceof OrderProducts)
        $this->products->removeElement($product);
    }

    /**
     * Get product
     *
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }


    /**
     * Add invoices
     *
     * @param Invoices $invoice
     * @return Order
     */
    public function addInvoice(Invoices $invoice=null)
    {
        if ( !$invoice->getOrderId() instanceof Invoice ) {
            $invoice->setOrderId($this);
        }

        if( !$this->invoices->contains($invoice))
        {
            $this->invoices->add($invoice);
        }
        return $this;
    }

    /**
     * Remove invoice
     *
     * @param OrderProducts $invoice
     */
    public function removeInvoice(Invoices $invoice)
    {
        if ($invoice instanceof Invoices)
        $this->invoices->removeElement($invoice);
    }

    /**
     * Get invoices
     *
     * @return Collection
     */
    public function getInvoices()
    {
        return $this->invoices;
    }


    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return OrderStatuses
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @param OrderStatuses $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getFromCountry()
    {
        return $this->fromCountry;
    }

    /**
     * @param string $fromCountry
     */
    public function setFromCountry($fromCountry)
    {
        $this->fromCountry = $fromCountry;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Set sendDetailHeight
     *
     * @param string $sendDetailHeight
     * @return Order
     */

    public function setSendDetailHeight($sendDetailHeight)
    {
        $this->sendDetailHeight = $sendDetailHeight;

        return $this;
    }

    /**
     * Get sendDetailHeight
     *
     * @return string
     */

    public function getSendDetailHeight()
    {
        return $this->sendDetailHeight;
    }

    /**
     * Set sendDetailWidth
     *
     * @param string $sendDetailWidth
     * @return Order
     */

    public function setSendDetailWidth($sendDetailWidth)
    {
        $this->sendDetailWidth = $sendDetailWidth;

        return $this;
    }

    /**
     * Get sendDetailWidth
     *
     * @return string
     */

    public function getSendDetailWidth()
    {
        return $this->sendDetailWidth;
    }

    /**
     * Set sendDetailLength
     *
     * @param string $sendDetailLength
     * @return Order
     */

    public function setSendDetailLength($sendDetailLength)
    {
        $this->sendDetailLength = $sendDetailLength;

        return $this;
    }

    /**
     * Get sendDetailLength
     *
     * @return string
     */

    public function getSendDetailLength()
    {
        return $this->sendDetailLength;
    }

    /**
     * Set sendDetailWeight
     *
     * @param string $sendDetailWeight
     * @return Order
     */

    public function setSendDetailWeight($sendDetailWeight)
    {
        $this->sendDetailWeight = $sendDetailWeight;

        return $this;
    }

    /**
     * Get sendDetailWeight
     *
     * @return string
     */

    public function getSendDetailWeight()
    {
        /* Round Weight to 0.5 */
        return ceil($this->sendDetailWeight/0.5)*0.5;
       // return $this->sendDetailWeight;
    }

    /**
     * Set sendDetailPlaces
     *
     * @param string $sendDetailPlaces
     * @return Order
     */

    public function setSendDetailPlaces($sendDetailPlaces)
    {
        $this->sendDetailPlaces = $sendDetailPlaces;

        return $this;
    }

    /**
     * Get sendDetailPlaces
     *
     * @return string
     */

    public function getSendDetailPlaces()
    {
        return $this->sendDetailPlaces;
    }

    /**
     * Set sendFromEmail
     *
     * @param string $sendFromEmail
     * @return Order
     */

    public function setSendFromEmail($sendFromEmail)
    {
        $this->sendFromEmail = $sendFromEmail;

        return $this;
    }

    /**
     * Get sendFromEmail
     *
     * @return string
     */

    public function getSendFromEmail()
    {
        return $this->sendFromEmail;
    }

    /**
     * Set sendFromPhone
     *
     * @param string $sendFromPhone
     * @return Order
     */

    public function setSendFromPhone($sendFromPhone)
    {
        $this->sendFromPhone = $sendFromPhone;

        return $this;
    }

    /**
     * Get sendFromPhone
     *
     * @return string
     */

    public function getSendFromPhone()
    {
        return $this->sendFromPhone;
    }

    /**
     * Set sendFromCity
     *
     * @param string $sendFromCity
     * @return Order
     */

    public function setSendFromCity($sendFromCity)
    {
        $this->sendFromCity = $sendFromCity;

        return $this;
    }

    /**
     * Get sendFromCity
     *
     * @return string
     */

    public function getSendFromCity()
    {
        return $this->sendFromCity;
    }

    /**
     * Set sendFromIndex
     *
     * @param string $sendFromIndex
     * @return Order
     */

    public function setSendFromIndex($sendFromIndex)
    {
        $this->sendFromIndex = $sendFromIndex;

        return $this;
    }

    /**
     * Get sendFromIndex
     *
     * @return string
     */

    public function getSendFromIndex()
    {
        return $this->sendFromIndex;
    }

    /**
     * Set sendFromAddress
     *
     * @param string $sendFromAdress
     * @return Order
     */

    public function setSendFromAddress($sendFromAddress)
    {
        $this->sendFromAddress = $sendFromAddress;

        return $this;
    }

    /**
     * Get sendFromAddress
     *
     * @return string
     */

    public function getSendFromAddress()
    {
        return $this->sendFromAddress;
    }

    /**
     * Set volumeWeigth
     *
     * @param string $volumeWeigth
     * @return Order
     */

    public function setVolumeWeigth($volumeWeigth)
    {
        $this->volumeWeigth = $volumeWeigth;

        return $this;
    }

    /**
     * Get volumeWeigth
     *
     * @return string
     */

    public function getVolumeWeigth()
    {
        return $this->volumeWeigth;
    }


    public function setDeclareValue($declareValue)
    {
        $this->declareValue = $declareValue;

        return $this;
    }

    public function getDeclareValue()
    {
        return $this->declareValue;
    }

    /**
     * tracking number for user
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=512, nullable=true)
     */

    public $trNum;


    /**
     * @return string
     */
    public function getTrNum()
    {
        return $this->trNum;
    }

    /**
     * @param string $trNum
     */
    public function setTrNum($trNum)
    {
        $this->trNum = $trNum;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="company_send_to_usa", type="string", length=512, nullable=true)
     */

    public $companySendToUsa;

    /**
     * @return string
     */
    public function getCompanySendToUsa()
    {
        return $this->companySendToUsa;
    }

    /**
     * @param string $companySendToUsa
     */
    public function setCompanySendToUsa($companySendToUsa)
    {
        $this->companySendToUsa = $companySendToUsa;
    }

    /**
     * tracking number for system
     * @var string
     *
     * @ORM\Column(name="system_number_to_usa", type="string", length=512, nullable=true)
     */

    public $systemNum;


    /**
     * @return string
     */
    public function getSystemNum()
    {
        return $this->systemNum;
    }

    /**
     * @param string $systemNum
     */
    public function setSystemNum($systemNum)
    {
        $this->systemNum = $systemNum;
    }

    /**
     *
     * @var string
     *
     * @ORM\Column(name="company_send_in_usa", type="string", length=512, nullable=true)
     */

    public $companySendInUsa;

    /**
     * @return string
     */
    public function getCompanySendInUsa()
    {
        return $this->companySendInUsa;
    }

    /**
     * @param string $companySendInUsa
     */
    public function setCompanySendInUsa($companySendInUsa)
    {
        $this->companySendInUsa = $companySendInUsa;
    }

    /**
     * tracking number for system
     * @var string
     *
     * @ORM\Column(name="system_number_in_usa", type="string", length=512, nullable=true)
     */

    public $systemNumInUsa;


    /**
     * @return string
     */
    public function getSystemNumInUsa()
    {
        return $this->systemNumInUsa;
    }

    /**
     * @param string $systemNumInUsa
     */
    public function setSystemNumInUsa($systemNumInUsa)
    {
        $this->systemNumInUsa = $systemNumInUsa;
    }
//    /**
//     * @ORM\OneToMany(targetEntity="DocumentDHL", mappedBy="ordersDHL",
//     *     cascade={"persist", "remove"}, orphanRemoval=true)
//     */
//    protected $documents;

//    /**
//     * Add documents
//     *
//     * @param \AppBundle\Entity\DocumentDHL $documents
//     * @return OrdersDHL
//     */
//    public function addDocument(\AppBundle\Entity\DocumentDHL $documents)
//    {
//        $this->documents[] = $documents;
//
//        return $this;
//    }

//    /**
//     * Set documents
//     *
//     * @param \AppBundle\Entity\DocumentDHL $documents
//     * @return OrdersDHL
//     */
//    public function setDocument(\AppBundle\Entity\DocumentDHL $documents)
//    {
//        $this->removeDocument($documents);
//        $this->addDocument($documents);
//
//        return $this;
//    }

//    /**
//     * Remove documents
//     *
//     * @param \AppBundle\Entity\DocumentDHL $documents
//     */
//    public function removeDocument(\AppBundle\Entity\DocumentDHL $documents)
//    {
//        $this->documents->removeElement($documents);
//    }
//
//    /**
//     * Get documents
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getDocuments()
//    {
//        return $this->documents;
//    }


    /**
     * @var array
     */
    public $paths;

    /**
     * Get paths
     *
     * @return string
     */
    public function getPaths()
    {
        return "";
    }

    /**
     * Set towarehouse
     *
     * @param string $towarehouse
     * @return Order
     */
    public function setToWarehouse($towarehouse)
    {
        $this->towarehouse = $towarehouse;

        return $this;
    }

    /**
     * Get towarehouse
     *
     * @return string
     */
    public function getToWarehouse()
    {
        return $this->towarehouse;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Order
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    /**
     * @var string
     *
     */

     public $custom;

    /**
     * Get custom
     *
     * @return integer
     */
    public function getCustom()
    {
        return $this->quantity;
    }

//    /**
//     * @var boolean
//     *
//     */
//     public $labelDhl;
//
//    /**
//     * Get labelDhl
//     *
//     * @return integer
//     */
//    public function getLabelDhl()
//    {
//        return false;
//    }

//    /**
//     * @var ShippingCompany
//     * @ORM\ManyToOne(targetEntity="ShippingCompany")
//     * @ORM\JoinColumn(name="shipping_company", referencedColumnName="id", nullable=true, onDelete="SET NULL")
//     */
//    public $shippingCompany;
//
//    /**
//     * Set shippingCompany
//     *
//     * @param string $shippingCompany
//     * @return OrdersDHL
//     */
//    public function setShippingCompany($shippingCompany)
//    {
//        $this->shippingCompany = $shippingCompany;
//
//        return $this;
//    }
//
//    /**
//     * Get shippingCompany
//     *
//     * @return string
//     */
//    public function getShippingCompany()
//    {
//        return $this->shippingCompany;
//    }


//    /**
//     * Set dhlLabel
//     *
//     * @param array $dhlLabel
//     * @return OrdersDHL
//     */
//    public function setDhlLabel($dhlLabel)
//    {
//        $this->dhlLabel = $dhlLabel;
//
//        return $this;
//    }
//
//    /**
//     * Get dhlLabel
//     *
//     * @return array
//     */
//    public function getDhlLabel()
//    {
//        return $this->dhlLabel;
//    }

    /**
     * @return string
     */
    public function getDeliveryStatus() {
        return $this->deliveryStatus;
    }

    /**
     * @param string $deliveryStatus
     * @return $this
     */
    public function setDeliveryStatus($deliveryStatus) {
        $this->deliveryStatus = $deliveryStatus;
        return $this;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="account_country", type="string", length=20, nullable=true)
     */
    public $accountCountry;

    /**
     * @return string
     */
    public function getAccountCountry()
    {
        return $this->accountCountry;
    }

    /**
     * @param string $accountCountry
     */
    public function setAccountCountry($accountCountry)
    {
        $this->accountCountry = $accountCountry;
    }

    public function getPermissionName()
    {
        return 'ordersdhl';
    }
    /**
     * @var
     * @ORM\Column(name="admin_create", type="boolean", nullable=true, options={"default":false})
     */
    private $adminCreate;

    public function setAdminCreate($adminCreate)
    {
        $this->adminCreate = $adminCreate;

        return $this;
    }

    public function getAdminCreate()
    {
        return $this->adminCreate;
    }

/**
 * @var string
 *
 */
    public $invoicesStr='';
}
