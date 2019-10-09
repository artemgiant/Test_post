<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\TransactionLiqPay;
/**
 * Invoices
 *
 * @ORM\Table(name="invoices")
 * @ORM\Entity
 */
class Invoices
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
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;


    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="invoices")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */

    private $orderId;

    /**
     * @ORM\OneToMany(targetEntity="TransactionLiqPay", mappedBy="invoice")
     *
     */
    private $transactions;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_paid", type="boolean")
     */
    private $isPaid = false;

    /**
     * @var string
     * @ORM\Column(name="form_token", type="string", length=4096, nullable=true)
     */
    public $formToken;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct() {
        $this->transactions = new ArrayCollection();
    }

       /**
     * Set order_id
     *
     * @param string $orderId
     * @return OrderProducts
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get order_id
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return AddPayment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
    public function isPaid()
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid)
    {
        $this->isPaid = $isPaid;

        return $this;
    }


    /**
     * Set price
     *
     * @param float $price
     * @return AddPayment
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }



    public function __toString() {
        if($this->getId()) {
            return '#' . $this->getId();
        } else {
            return 'New orders add payment';
        }
    }

    public function addTransaction(TransactionLiqPay $transaction)
    {
        if ($this->transactions->contains($transaction)) {
            return;
        }

        $this->transactions[] = $transaction;
        $transaction->setOrder($this);
    }
    /**
     * @return Collection|TransactionLiqPay[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    public function removeTransaction(TransactionLiqPay $transaction)
    {
        $this->transactions->removeElement($transaction);
        // установите владеющую сторону, как null
        $transaction->setOrder(null);
    }

    public $order;

    /**
     * @return string
     */
    public function getFormToken()
    {
        return $this->formToken;
    }

    /**
     * @param string $formToken
     * @return $this
     */
    public function setFormToken($formToken)
    {
        $this->formToken = $formToken;
        return $this;
    }
}
