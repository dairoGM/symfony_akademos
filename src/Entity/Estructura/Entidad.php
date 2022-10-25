<?php

namespace App\Entity\Estructura;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbd_entidad")
 * @UniqueEntity(fields="codigo", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class Entidad extends BaseNomenclator
{
    /**
     * @ORM\OneToOne(targetEntity="Estructura")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Estructura $estructura;

    /**
     * @ORM\ManyToOne(targetEntity="TipoEntidad")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?TipoEntidad $tipoEntidad;

    /**
     * Entiddad padre
     * 
     * @ORM\ManyToOne(targetEntity="Entidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Entidad $entidad;

    /**
     * @ORM\Column(type="string", length=180, nullable=false)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=15, nullable=false)
     */
    private ?string $telefono;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private ?string $direccion;


    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private ?string $codigo;


    /**
     * @ORM\ManyToOne(targetEntity="Municipio")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Municipio $municipio;

    /**
     * @ORM\ManyToOne(targetEntity="Provincia")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Provincia $provincia;
    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private ?string $siglas;

    public function getEstructura()
    {
        return $this->estructura;
    }

    public function setEstructura($estructura)
    {
        $this->estructura = $estructura;

        return $this;
    }

    public function getTipoEntidad()
    {
        return $this->tipoEntidad;
    }

    public function setTipoEntidad($tipoEntidad)
    {
        $this->tipoEntidad = $tipoEntidad;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }


    public function getSiglas()
    {
        return $this->siglas;
    }

    public function setSiglas($siglas)
    {
        $this->siglas = $siglas;

        return $this;
    }

    public function getMunicipio()
    {
        return $this->municipio;
    }

    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;

        return $this;
    }

    public function getProvincia()
    {
        return $this->provincia;
    }

    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get entiddad padre
     */ 
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * Set entiddad padre
     *
     * @return  self
     */ 
    public function setEntidad($entidad)
    {
        $this->entidad = $entidad;

        return $this;
    }
}
