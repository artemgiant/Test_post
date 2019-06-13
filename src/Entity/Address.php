<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;



    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string")
     */
    private $country;


    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string")
     */
    private $address;

    /**
     * @var string
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
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     * @var boolean
     *
     * @ORM\Column(name="my_address", type="boolean", nullable=true)
     */
    private $my_address;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string")
     */
    private $zip;


    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string")
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="house", type="string")
     */
    private $house;

    /**
     * @var string
     *
     * @ORM\Column(name="appartment", type="string", nullable=true)
     */
    private $appartment;

    /**
     * @var string
     *
     * @ORM\Column(name="user_first_name", type="string")
     */
    private $userFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="user_last_name", type="string")
     */
    private $userLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="user_second_name", type="string", nullable=true)
     */
    private $userSecondName;


    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

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
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Address
     */
    public function setCountry(string $country): Address
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

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Address
     */
    public function setAddress(string $address): Address
    {
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
    public function isMyAddress(): ?bool
    {
        return $this->my_address;
    }

    /**
     * @param bool $my_address
     * @return Address
     */
    public function setMyAddress(bool $my_address): Address
    {
        $this->my_address = $my_address;
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
    public function setRegionRayon(string $regionRayon): Address
    {
        $this->regionRayon = $regionRayon;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return Address
     */
    public function setStreet(string $street): Address
    {
        $this->street = $street;
        return $this;
    }


    /**
     * @return string
     */
    public function getHouse(): ?string
    {
        return $this->house;
    }

    /**
     * @param string $house
     * @return Address
     */
    public function setHouse(string $house): Address
    {
        $this->house = $house;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppartment(): ?string
    {
        return $this->appartment;
    }

    /**
     * @param string $appartment
     * @return Address
     */
    public function setAppartment(string $appartment): Address
    {
        $this->appartment = $appartment;
        return $this;
    }

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


}
