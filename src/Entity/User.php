<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

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

    public $agreed = false;

    public function getId()
    {
        return $this->id;
    }

    public function getRoles()
    {
         if(is_array($this->roles))
           return $this->roles;

       return json_decode($this->roles,1);
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
     * @ORM\PrePersist
     */
    public function convertRoles()
    {
        $this->setRoles($this->roles);

        return $this;
    }
}
