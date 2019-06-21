<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderProducts
 *
 * @ORM\Table(name="add_payment")
 * @ORM\Entity
 */
class AddPayment
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
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="products", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */

    private $orderId;

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
}
