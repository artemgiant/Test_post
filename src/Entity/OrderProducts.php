<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderProducts
 *
 * @ORM\Table(name="order_products")
 * @ORM\Entity
 */
class OrderProducts
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
     * @ORM\Column(name="desc_en", type="text", nullable=true)
     */
    private $descEn;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_ua", type="text", nullable=true)
     */
    private $descUa;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=true)
     */
    private $count;

    /**
     * @var float
     *
     * @ORM\Column(name="total_summ", type="float", nullable=true)
     */
    private $totalSumm;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="Order")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $order_id;

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
     * Set count
     *
     * @param integer $count
     * @return OrderProducts
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set order_id
     *
     * @param string $order_id
     * @return OrderProducts
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;

        return $this;
    }

    /**
     * Get order_id
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * Set descEn
     *
     * @param string $descEn
     * @return OrderProducts
     */
    public function setDescEn($descEn)
    {
        $this->descEn = $descEn;

        return $this;
    }

    /**
     * Get descEn
     *
     * @return string
     */
    public function getDescEn()
    {
        return $this->descEn;
    }

    /**
     * Set descUa
     *
     * @param string $descUa
     * @return OrderProducts
     */
    public function setDescUa($descUa)
    {
        $this->descUa = $descUa;

        return $this;
    }

    /**
     * Get descUa
     *
     * @return string
     */
    public function getDescUa()
    {
        return $this->descUa;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return OrderProducts
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

    /**
     * Set totalSumm
     *
     * @param float $totalSumm
     * @return OrderProducts
     */
    public function setTotalSumm($totalSumm)
    {
        $this->totalSumm = $totalSumm;

        return $this;
    }

    /**
     * Get totalSumm
     *
     * @return float
     */
    public function getTotalSumm()
    {
        return $this->totalSumm;
    }
}
