<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, \Serializable
{
    const DEFAULT_ROLE = 'ROLE_POST_USER';
    const ADMIN_ROLE = 'ROLE_SUPER_ADMIN';
    const POST_ROLE = 'ROLE_POST_USER';
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", nullable=true)
     *
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="second_name", type="string", nullable=true)
     */
    private $secondName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", unique=true)
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", unique=true, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="TransactionLiqPay", mappedBy="user")
     */
    private $transaction;

  /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="user")
     */
    private $addresses;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    private $password;

    /**
     * A non-persisted field that's used to create the encoded password.
     * @Assert\Length(
     *      min = 6,
     *      minMessage = "Your password must be at least {{ limit }} characters long.",
     * )
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="roles", type="text", nullable=true)
     */
    private $roles = [self::DEFAULT_ROLE];

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_suspended", type="boolean")
     */
    private $isSuspended = false;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", nullable=true)
     *
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", nullable=true)
     *
     */
    private $locale;

    public $agreed = false;

    public function getId()
    {
        return $this->id;
    }

    public function getRoles()
    {
       return json_decode(stripslashes(trim($this->roles,'"')),1);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;

        return $this;
    }

    public function setPlainPassword(string $plainPassword = null)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;

        return $this;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setRoles($roles)
    {
         $this->roles = json_encode($roles);

       return $this;
    }

    public function addRole($role)
    {
        if (!$role) {
            return $this;
        }

        $role = strtoupper($role);
        $roles=$this->getRoles();

        if (is_array($roles) && !in_array($role,$roles , true)) {
            $roles[] = $role;
            $this->setRoles($roles);
        }

        return $this;
    }

    public function removeRole($role)
    {
        $roles=$this->getRoles();
        if (is_array($roles) && false !== $key = array_search(strtoupper($role), $roles, true)) {
            unset($roles[$key]);
           $this->setRoles(array_values($roles));
        }

        return $this;
    }

    public function hasRole($role)
    {
        $roles=$this->getRoles();

        return in_array(strtoupper($role), $roles, true);
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTime $lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function isSuspended()
    {
        return $this->isSuspended;
    }

    public function setIsSuspended(bool $isSuspended)
    {
        $this->isSuspended = $isSuspended;

        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->roles,
            $this->isSuspended
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->roles,
            $this->isSuspended
            ) = unserialize($serialized);
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
            return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
            return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    /**
     * @param string $secondName
     * @return User
     */
    public function setSecondName(?string $secondName): User
    {
        $this->secondName = $secondName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        if($this->phone)
        {
            return $this->phone;
        }

        $this->phone = '';
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return User
     */
    public function setPhone(?string $phone): User
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param mixed $addresses
     * @return User
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): ?string
    {
        if($this->avatar)
        {
            return $this->avatar;
        }

        $this->avatar = '';
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar(?string $avatar): User
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function convertRoles()
    {
        $this->setRoles($this->roles);

        return $this;
    }

    /*
     *
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/jpg" })
     *
     */

    public  $avatarFile;

    /**
     * @return string
     */
    public function __toString()
    {
        if (!$this->firstName && !$this->lastName)
        {
            return $this->email;
        }

        return $this->firstName . ' ' . $this->lastName;
    }

     /**
     * @var string
     *
     */
    public $country;


    /**
     * @var string
     *
     */
    public $regionOblast;

    /**
     * @var string
     *
     */
    public $regionRayon;

    /**
     * @var string
     *
     */
    public $city;

    /**
     * @var string
     *
     */
    public $zip;

    /**
     * @var string
     *
     */
    public $street;

    /**
     * @var string
     *
     */

    public $house;
    public  $apartment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isWip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $markup;

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    public function getIsWip(): ?bool
    {
        return $this->isWip;
    }

    public function setIsWip(?bool $isWip): self
    {
        $this->isWip = $isWip;

        return $this;
    }

    public function getMarkup(): ?string
    {
        return $this->markup;
    }

    public function setMarkup(string $markup): self
    {
        $this->markup = $markup;

        return $this;
    }

}