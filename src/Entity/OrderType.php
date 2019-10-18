<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * OrderType
 *
 * @ORM\Table(name="order_type")
 * @ORM\Entity
 */
class OrderType
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
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_ru", type="string", length=255, nullable=true)
     */
    private $nameRu;


    /**
     * @ORM\OneToMany(targetEntity="PriceForDeliveryType", mappedBy="ordertype", cascade={"persist", "remove"})
     */
    private $pricetype;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return string
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @param string $name
     * @return string
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNameRu(){
        return $this->nameRu;
    }
    /**
     * @param string $nameRu
     * @return string
     */
    public function setNameRu(string $nameRu): self
    {
        $this->nameRu = $nameRu;

        return $this;
    }

    public function __construct()
    {
        $this->pricetype = new ArrayCollection();
    }


    /**
     * Add invoices
     *
     * @param PriceForDeliveryType $invoice
     * @return OrderType
     */
    public function addPricetype(PriceForDeliveryType $invoice=null)
    {
        if ( !$invoice->getOrderId() instanceof PriceForDeliveryType ) {
            $invoice->setOrderId($this);
        }

        if( !$this->pricetype->contains($invoice))
        {
            $this->pricetype->add($invoice);
        }
        return $this;
    }

    /**
     * Remove invoice
     *
     * @param PriceForDeliveryType $invoice
     */
    public function removePricetype(PriceForDeliveryType $invoice)
    {
        if ($invoice instanceof PriceForDeliveryType)
            $this->pricetype->removeElement($invoice);
    }

    /**
     * Get invoices
     *
     * @return Collection
     */
    public function getPricetype()
    {
        return $this->pricetype;
    }

    /**
     * return string
     */
    public function __toString()
    {
        if (!empty($this->getName()))
            return $this->getName();

        return '';
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        $locale = '';
        if(isset($GLOBALS['request']) && $GLOBALS['request']) $locale = $GLOBALS['request']->getLocale();
        if (!empty($locale) && $locale!='ua') return $this->{"name".ucfirst($locale)};
        else return $this->name;
    }
}
