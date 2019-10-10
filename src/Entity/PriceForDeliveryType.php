<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="PriceForDeliveryType")
 * @ORM\Entity(repositoryClass="App\Repository\PriceEconomRepository")
 * @ORM\HasLifecycleCallbacks()
 */

class PriceForDeliveryType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $max_weight;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vip;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @var OrderType
     * @ORM\ManyToOne(targetEntity="OrderType", inversedBy="pricetype")
     * @ORM\JoinColumn(name="order_type", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */

    private $ordertype;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxWeight(): ?float
    {
        return $this->max_weight;
    }

    public function setMaxWeight(float $max_weight): self
    {
        $this->max_weight = $max_weight;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }


    public function setOrdertype($ordertype)
    {
        $this->ordertype = $ordertype;

        return $this;
    }


    public function getOrdertype()
    {
        return $this->ordertype;
    }

    public function setVip($vip)
    {
        $this->vip = $vip;

        return $this;
    }


    public function isVip()
    {
        return $this->vip;
    }
}
