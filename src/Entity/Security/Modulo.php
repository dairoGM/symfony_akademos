<?php

namespace App\Entity\Security;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Define un módulo en el sistema.
 * Un módulo tiene varias funcionalidades.
 *
 * @ORM\Entity
 * @ORM\Table(name="seguridad.tbd_modulo")
 * @UniqueEntity(fields="nombre", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class Modulo extends BaseEntity
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
    private $moduleKey;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;

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

    public function securityEnabled()
    {
        return $this->activo;
    }

    /**
     * Get the value of moduleKey
     */ 
    public function getModuleKey()
    {
        return $this->moduleKey;
    }

    /**
     * Set the value of moduleKey
     *
     * @return  self
     */ 
    public function setModuleKey($moduleKey)
    {
        $this->moduleKey = $moduleKey;

        return $this;
    }
}
