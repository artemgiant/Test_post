<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Address
 *
 * @ORM\Table(name="addresses")
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Address
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="addresses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */

    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="addresses")
     */

    private $order;

//    /**
//     * @var string
//     *
//     * @Assert\NotBlank
//     *
//     * @ORM\Column(name="country", type="string")
//     */
//    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="region_oblast", type="string")
     */
    private $regionOblast;

    /**
     * @var string
     *
     * @ORM\Column(name="region_rayon", type="string", nullable=true)
     */
    private $regionRayon;

    /**
     * @var string
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_my_address", type="boolean", nullable=true)
     */
    private $isMyAddress = false;

    /**
     * @var string
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="zip", type="string")
     */
    private $zip;

//    /**
//     * @var string
//     *
//     * @Assert\NotBlank
//     *
//     * @ORM\Column(name="street", type="string")
//     */
//    private $street;

//    /**
//     * @var string
//     *
//     * @Assert\NotBlank
//     *
//     * @ORM\Column(name="house", type="string")
//     */
//    private $house;

//    /**
//     * @var string
//     *
//     * @ORM\Column(name="apartment", type="string", nullable=true)
//     */
//    private $apartment;

    /**
     * @var string
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="user_first_name", type="string")
     */
    private $userFirstName;

    /**
     * @var string
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="user_last_name", type="string")
     */
    private $userLastName;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="user_second_name", type="string", nullable=true)
     */
    private $userSecondName;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="alias_of_address", type="string", nullable=true)
     */
    private $aliasOfAddress;

    /**
     * @var string
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $country;

    public function __construct()
    {
        $this->order = new ArrayCollection();
    }

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirmed", type="boolean")
     */
    private $isConfirmed = false;

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
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     * @return Address
     */
    public function setZip(string $zip): Address
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Address
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Address
     */
    public function setCity(string $city): Address
    {
        $this->city = $city;
        return $this;
    }

//    /**
//     * @return string
//     */
//    public function getAddress(): ?string
//    {
//        return $this->address;
//    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
//        return $this->street.' str.,'.$this->apartment.'ap.';
        return $this->address;
    }

    /**
     * @return Address
     */
    public function setAddress($address): Address
    {
//        $address = '';
//        if ($this->street)
//            $address.=$this->street.' str.';
//        if ($this->house)
//            $address.=', '.$this->house;
//        if ($this->apartment)
//            $address.=', ap. '.$this->apartment;

        $this->address = $address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Address
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMyAddress()
    {
        return $this->isMyAddress;
    }

    /**
     * @param integer $isMyAddress
     * @return Address
     */
    public function setIsMyAddress($isMyAddress)
    {
        $this->isMyAddress = $isMyAddress;
        return $this;
    }
    /**
     * @return string
     */
    public function getRegionOblast(): ?string
    {
        return $this->regionOblast;
    }

    /**
     * @param string $regionOblast
     * @return Address
     */
    public function setRegionOblast(string $regionOblast): Address
    {
        $this->regionOblast = $regionOblast;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionRayon(): ?string
    {
        return $this->regionRayon;
    }

    /**
     * @param string $regionRayon
     * @return Address
     */
    public function setRegionRayon($regionRayon): Address
    {
        if ($regionRayon)
            $this->regionRayon = $regionRayon;
        else
            $this->regionRayon = ' ';

        return $this;
    }

//    /**
//     * @return string
//     */
//    public function getStreet(): ?string
//    {
//        return $this->street;
//    }
//
//    /**
//     * @param string $street
//     * @return Address
//     */
//    public function setStreet(string $street): Address
//    {
//        $this->street = $street;
//        return $this;
//    }


//    /**
//     * @return string
//     */
//    public function getHouse(): ?string
//    {
//        return $this->house;
//    }
//
//    /**
//     * @param string $house
//     * @return Address
//     */
//    public function setHouse(string $house): Address
//    {
//        $this->house = $house;
//        return $this;
//    }

    /**
     * @return string
     */
    public function getUserFirstName(): ?string
    {
        return $this->userFirstName;
    }

    /**
     * @param string $userFirstName
     * @return Address
     */
    public function setUserFirstName(string $userFirstName): Address
    {
        $this->userFirstName = $userFirstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserLastName(): ?string
    {
        return $this->userLastName;
    }

    /**
     * @param string $userLastName
     * @return Address
     */
    public function setUserLastName(string $userLastName): Address
    {
        $this->userLastName = $userLastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserSecondName(): ?string
    {
        return $this->userSecondName;
    }

    /**
     * @param string $userSecondName
     * @return Address
     */
    public function setUserSecondName(string $userSecondName): Address
    {
        $this->userSecondName = $userSecondName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Address
     */
    public function setPhone(string $phone): Address
    {
        $this->phone = $phone;
        return $this;
    }

//    /**
//     * @return string
//     */
//    public function getApartment(): ?string
//    {
//        return $this->apartment;
//    }
//
//    /**
//     * @param string $apartment
//     * @return Address
//     */
//    public function setApartment(string $apartment): Address
//    {
//        $this->apartment = $apartment;
//        return $this;
//    }

    /**
     * @return string
     */
    public function getAliasOfAddress(): ?string
    {
        return $this->aliasOfAddress;
    }

    /**
     * @param string $aliasOfAddress
     * @return Address
     */
    public function setAliasOfAddress(string $aliasOfAddress): Address
    {
        $this->aliasOfAddress = $aliasOfAddress;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFullName(): string
    {
        return trim($this->userFirstName.' '.$this->userLastName.' '.$this->userSecondName);
    }

    /**
     *
     * @return string
     */
    public function getFullAddress(): string
    {
        $fullAddress = trim($this->address.' '.$this->city.' '.$this->zip.' '.$this->regionOblast.' '.$this->regionRayon);
        if (!$fullAddress)
            return ' ';
        return $fullAddress;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    /**
     * @param bool $isConfirmed
     * @return Address
     */
    public function setIsConfirmed(bool $isConfirmed): Address
    {
        $this->isConfirmed = $isConfirmed;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString(): string
    {
        return trim($this->street.' '.$this->house.' '.$this->apartment.' '.$this->city.' '.$this->zip.' '.$this->regionOblast.' '.$this->regionRayon);
    }
}
