<?php

namespace App\Entity\Security;

use App\Entity\BaseEntity;
use App\Entity\Personal\Persona;
use App\ExtendSys\Chat\Model\ChatUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuario Local del Sistema
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 * @ORM\Table(name="seguridad.tbd_usuario")
 */
class User extends BaseEntity implements UserInterface, AuthorityInterface
{
    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
     */
    private $password;

    /**
     * @var string Rol principal/especial
     * @ORM\Column(type="string", nullable=false)
     */
    private $role = 'ROLE_USER';

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;

    /**
     * Roles asignados al usuario.
     *
     * @ORM\ManyToMany(targetEntity="Rol", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinTable(name="seguridad.tbr_user_rol",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id", onDelete="cascade")}
     *      )
     */
    private $userRoles;    

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $passwordChangeFirstTime = false;

    private $passwordPlainOld;

    private $passwordPlainText;

    public function __construct()
    {
        $this->userRoles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        //Roles
        $roles = $this->getAuthorities();

        //Guarantee every user at least
        $roles[] = $this->role;

        return array_unique($roles);
    }

    /**
     * Devuelve una array con los authorities (permisos o roles)
     *
     * @return string[]
     */
    public function getAuthorities(): array
    {
        //Authorities
        $authorities = [];

        foreach ($this->userRoles as $rol) {
            if ($rol->getActivo()) {
                $authorities = array_merge($authorities, $rol->getAuthorities());
            }
        }

        return array_unique($authorities);
    }

    /**
     * Devuelve si la Authority esta habilitada
     *
     * @return boolean
     */
    function authorityEnabled(): bool
    {
        return $this->activo;
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getActivo()
    {
        return $this->activo;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    public function getUserRoles()
    {
        return $this->userRoles;
    }

    public function setUserRoles($userRoles)
    {
        $this->userRoles = $userRoles;

        return $this;
    }

    /**
     * Get rol principal/especial
     *
     * @return  string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set rol principal/especial
     *
     * @param string $role Rol principal/especial
     *
     * @return  self
     */
    public function setRole(string $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of passwordPlainOld
     */
    public function getPasswordPlainOld()
    {
        return $this->passwordPlainOld;
    }

    /**
     * Set the value of passwordPlainOld
     *
     * @return  self
     */
    public function setPasswordPlainOld($passwordPlainOld)
    {
        $this->passwordPlainOld = $passwordPlainOld;

        return $this;
    }

    /**
     * Get the value of passwordPlainText
     */
    public function getPasswordPlainText()
    {
        return $this->passwordPlainText;
    }

    /**
     * Set the value of passwordPlainText
     *
     * @return  self
     */
    public function setPasswordPlainText($passwordPlainText)
    {
        $this->passwordPlainText = $passwordPlainText;

        return $this;
    }
    public function getPasswordChangeFirstTime()
    {
        return $this->passwordChangeFirstTime;
    }

    public function setPasswordChangeFirstTime($passwordChangeFirstTime)
    {
        $this->passwordChangeFirstTime = $passwordChangeFirstTime;

        return $this;
    }   
}
