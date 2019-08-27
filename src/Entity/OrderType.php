<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
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

    /**
     * return string
     */
    public function __toString()
    {
        if (!$this->getCode())
            return $this->getCode();

        return $this->name;
    }

}
