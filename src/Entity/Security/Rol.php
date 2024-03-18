<?php

namespace App\Entity\Security;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Define un Rol.
 * Un Rol puede ser asignado a varios usuarios. Un rol tiene permiso a varias Funcionalidades
 * @ORM\Entity
 * @UniqueEntity(fields="nombre", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 * @ORM\Table(name="seguridad.tbd_rol")
 */
class Rol extends BaseEntity implements AuthorityInterface
{
    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZäëïöüáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", length=180, nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZäëïöüáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    private $roleKey;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;

    /**
     * Funcionalidades a las que tiene permiso el Rol.
     *
     * @ORM\ManyToMany(targetEntity="Funcionalidad", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinTable(name="seguridad.tbr_rol_funcionalidad",
     *      joinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id",  onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="funcionalidad_id", referencedColumnName="id",  onDelete="cascade")}
     *      )
     */
    private $funcionalidades;

    public function __construct()
    {
        $this->funcionalidades = new \Doctrine\Common\Collections\ArrayCollection();
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

        foreach ($this->funcionalidades as $fun) {
            if ($fun->getActivo()) {
                $authorities = array_merge($authorities, $fun->getAuthorities());
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

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
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

    public function getFuncionalidades()
    {
        return $this->funcionalidades;
    }

    public function setFuncionalidades($funcionalidades)
    {
        $this->funcionalidades = $funcionalidades;

        return $this;
    }

    /**
     * Get the value of roleKey
     */ 
    public function getRoleKey()
    {
        return $this->roleKey;
    }

    /**
     * Set the value of roleKey
     *
     * @return  self
     */ 
    public function setRoleKey($roleKey)
    {
        $this->roleKey = $roleKey;

        return $this;
    }
}
