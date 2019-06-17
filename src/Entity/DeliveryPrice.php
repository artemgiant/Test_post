<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="delivery_price")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class DeliveryPrice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var float
     * @ORM\Column(name="cost", type="float", nullable=true)
     */
    private $cost;

    /**
     * @var float
     * @ORM\Column(name="weight", type="float", nullable=true)
     */
    private $weight;



    public function __toString()
    {
        return $this->weight."kg - $".$this->cost;
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
     * Set cost
     *
     * @param float $cost
     * @return DeliveriPrice
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set weight
     *
     * @param float $weight
     * @return DeliveriPrice
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

}
