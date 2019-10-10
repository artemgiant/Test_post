<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\Invoices;

/**
 * @ORM\Table(name="transaction_authorize")
 * @ORM\Entity(repositoryClass="App\Repository\TransactionLiqPayRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TransactionAutorize
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
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=512, nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="sum", type="string", length=512, nullable=true)
     */
    private $sum;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=512, nullable=true)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=512, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=512, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=512, nullable=true)
     */
    protected $phoneNumber;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User", inversedBy="transaction")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Invoices",inversedBy="transactions")
     * @ORM\JoinColumn(name="invoice", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $invoice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     * @ORM\Column(name="liqpay_order_id", type="string", length=512, nullable=true)
     */
    private $liqpayOrderId;

    /**
     * @var string
     *
     * @ORM\Column(name="liqpay_info", type="text", nullable=true)
     */
    private $liqpayInfo;

    /**
     * Set number
     *
     * @param string $number
     * @return TransactionLiqPay
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
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

    /**
     * Set user
     *
     * @param User $user
     * @return TransactionLiqPay
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set invoice
     *
     * @param Invoices $invoice
     * @return TransactionLiqPay
     */
    public function setInvoice(Invoices $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return Invoices
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->getNumber();
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return TransactionLiqPay
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

     /**
     * Set lastName
     *
     * @param string $lastName
     * @return TransactionLiqPay
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getFullName()
    {
       // return $this->firstName." ".$this->middleName." ".$this->lastName;
        return $this->lastName." ".$this->middleName;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return TransactionLiqPay
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TransactionLiqPay
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

    public function setLiqpayOrderId($liqpayOrderId)
    {
        $this->liqpayOrderId = $liqpayOrderId;

        return $this;
    }


    public function getLiqpayOrderId()
    {
        return $this->liqpayOrderId;
    }



    public function setSum($sum)
    {
        $this->sum = $sum;

        return $this;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setLiqpayInfo($liqpayInfo)
    {
        $this->liqpayInfo = $liqpayInfo;

        return $this;
    }

    public function getLiqpayInfo()
    {
        return $this->liqpayInfo;
    }


}
