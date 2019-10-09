<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AuthorizeDotNetInvoice
 *
 * @ORM\Entity()
 * @ORM\Table(name="authorize_dot_net_invoices")
 * @ORM\HasLifecycleCallbacks
 */
class AuthorizeDotNetInvoice
{
    /**
     * Fields
     */

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    public $user;

    /**
     * @var float
     * @ORM\Column(name="amount", type="float", scale=2, nullable=true)
     */
    public $amount;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=1024)
     */
    public $title;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var boolean
     * @ORM\Column(name="is_payed", type="boolean", nullable=true)
     */
    public $isPayed;

    /**
     * @var string
     * @ORM\Column(name="form_token", type="string", length=4096, nullable=true)
     */
    public $formToken;

    /**
     * @var string
     * @ORM\Column(name="pay_pal_link", type="string", length=2048, nullable=true)
     */
    public $payPalLink;

    /**
     * @var Listing
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Listing", mappedBy="authorizeDotNetInvoice")
     */
    public $listing;

    /**
     * @var boolean
     * @ORM\Column(name="is_no_comission", type="boolean", nullable=true)
     */
    public $isNoComission;

    /**
     * Methods
     */

    /**
     * AuthorizeDotNetInvoice constructor
     */

    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->setIsNoComission(false);
    }

    /**
     * Getters and setters
     */

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPayed()
    {
        return $this->isPayed;
    }

    /**
     * @param bool $isPayed
     * @return $this
     */
    public function setIsPayed($isPayed)
    {
        $this->isPayed = $isPayed;
        return $this;
    }

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

    /**
     * @return string
     */
    public function getPayPalLink()
    {
        return $this->payPalLink;
    }

    /**
     * @param string $payPalLink
     * @return $this
     */
    public function setPayPalLink($payPalLink)
    {
        $this->payPalLink = $payPalLink;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate() {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return Listing
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @param Listing $listing
     * @return $this
     */
    public function setListing($listing)
    {
        $this->listing = $listing;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNoComission()
    {
        return $this->isNoComission;
    }

    /**
     * @param bool $isPayed
     * @return $this
     */
    public function setIsNoComission($isNoComission)
    {
        $this->isNoComission = $isNoComission;
        return $this;
    }
}