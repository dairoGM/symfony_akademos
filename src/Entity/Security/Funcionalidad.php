<?php

namespace App\Entity\Security;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Representa una Funcionalidad o Permiso (authority) enel sistema.
 * Puede ser asignada a varios roles.
 * Es parte de un módulo.
 * No debe ser gestionada (las funcionalidad deben existir en el sistema de antemano).
 * Sólo debe ser posible editar su nombre, descripción y su campo activo.
 *
 * @ORM\Entity
 * @ORM\Table(name="seguridad.tbd_funcionalidad")
 * @UniqueEntity(fields="nombre", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class Funcionalidad extends BaseEntity implements AuthorityInterface
{
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZäëïöüáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZäëïöüáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private $descripcion;

    /**
     * Define un permiso (authority) para la funcionalidad :
     *    Ejemplo: ROLE_MNG_PERSONA  -> Para la Funcionalidad o Permiso de gestionar personas.
     *
     * Deben emepzar simpre con ROLE_
     * No debe ser editado
     *
     * @ORM\Column(type="string", length=250, unique=true, nullable=false)
     */
    private $roleKey;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;

    /**
     * @ORM\ManyToOne(targetEntity="Modulo", fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Modulo $modulo;

    /**
     * Devuelve una array con los authorities (permisos o roles)
     *
     * @return string[]
     */
    public function getAuthorities(): array
    {
        //Authorities
        $authorities = [];

        $authorities[] = $this->roleKey;

        return $authorities;
    }

    /**
     * Devuelve si la Authority esta habilitada
     *
     * @return boolean
     */
    function authorityEnabled(): bool
    {
        return $this->activo && (null == $this->modulo || $this->modulo->getActivo());
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

    public function getRoleKey()
    {
        return $this->roleKey;
    }

    public function setRoleKey($roleKey)
    {
        $this->roleKey = $roleKey;

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

    public function getModulo()
    {
        return $this->modulo;
    }

    public function setModulo($modulo)
    {
        $this->modulo = $modulo;

        return $this;
    }
}
