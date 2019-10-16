<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CouponRepository")
 */
class Coupon
{
    public function __construct()
    {
        $this->Code=$this->genareteCode();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="coupons")
     */
    private $UserCoupon;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ShippingType;



    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Discount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserCoupon(): ?User
    {
        return $this->UserCoupon;
    }

    public function setUserCoupon(?User $UserCoupon): self
    {
        $this->UserCoupon = $UserCoupon;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(?string $Code): self
    {
        $this->Code = $Code;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(?string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getShippingType(): ?string
    {
        return $this->ShippingType;
    }

    public function setShippingType(?string $ShippingType): self
    {
        $this->ShippingType = $ShippingType;

        return $this;
    }

    private function genareteCode()
    {
        return str_pad(str_pad(dechex(mt_rand(0, 0xFFFFFF)), 19, dechex(mt_rand(0, 0xFFFFFF)), STR_PAD_RIGHT),30,dechex(time()),STR_PAD_RIGHT);
    }

    public function getOrderCoupon(): ?Order
    {
        return $this->OrderCoupon;
    }

    public function setOrderCoupon(?Order $OrderCoupon): self
    {
        $this->OrderCoupon = $OrderCoupon;

        // set (or unset) the owning side of the relation if necessary
        $newCoupon = $OrderCoupon === null ? null : $this;
        if ($newCoupon !== $OrderCoupon->getCoupon()) {
            $OrderCoupon->setCoupon($newCoupon);
        }

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->Discount;
    }

    public function setDiscount(?int $Discount): self
    {
        $this->Discount = $Discount;

        return $this;
    }
}
