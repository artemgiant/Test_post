<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderStatus
 *
 * @ORM\Table(name="order_statuses")
 * @ORM\Entity
 */
class OrderStatus
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
    * @ORM\Column(name="status", type="string", length=255, nullable=true)
    */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="status_name", type="string", length=255, nullable=true)
     */
    private $statusName;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return string
     */
    public function setStatus(?string $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusName(): ?string
    {
        return $this->statusName;
    }

    /**
     * @param string $statusName
     * @return string
     */
    public function setStatusName(?string $statusName)
    {
        $this->statusName = $statusName;
        return $this;
    }

    /**
     * return string
     */
    public function __toString()
    {
        if (!$this->getStatusName())
            return $this->getStatus();

        return $this->statusName;
    }
}
