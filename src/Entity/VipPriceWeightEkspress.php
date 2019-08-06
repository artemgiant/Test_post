<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VipPriceWeightEkspressRepository")
 */
class VipPriceWeightEkspress
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $price_weight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceWeight(): ?string
    {
        return $this->price_weight;
    }

    public function setPriceWeight(?string $price_weight): self
    {
        $this->price_weight = $price_weight;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }
}
