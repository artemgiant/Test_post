<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity
 * @ORM\Table(name="transaction_liq_pay")
 * @ORM\Entity(repositoryClass="App\Repository\TransactionLiqPayRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TransactionLiqPay
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="accounts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="Order", mappedBy="transaction")
     * @ORM\JoinColumn(name="order", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", length=512, nullable=true)
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
     * @return Lot
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set order
     *
     * @param Order $order
     * @return TransactionLiqPay
     */
    public function setOrder(Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
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
     * @return Account
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
     * @return Account
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
     * @return Account
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
     * @return Account
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
