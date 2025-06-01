<?php

namespace App\Entity\Estructura;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="estructura.tbd_estructura")
 * @UniqueEntity(fields="nombre", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 * @UniqueEntity(fields="siglas", message="EL valor {{ value }} ya existe. Por favor inserte otro valor.")
 */
class Estructura extends BaseEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $nombre;

    /**
     * @ORM\Column(type="string", nullable=true, length="15")
     */
    private ?string $siglas;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $activo = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $esEntidad = false;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $centroAutorizadoPosgrado = false;


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $fechaActivacion;

    /**
     * @ORM\ManyToOne(targetEntity="TipoEstructura")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?TipoEstructura $tipoEstructura;

    /**
     * Estructura padre
     *
     * @ORM\ManyToOne(targetEntity="Estructura")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Estructura $estructura;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaEstructura")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CategoriaEstructura $categoriaEstructura;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $ubicacion;
    /**
     * @ORM\ManyToOne(targetEntity="Nivel")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Nivel $nivel;


    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=15, nullable=true                                                                                                                                                                                                                                              )
     */
    private ?string $telefono;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private ?string $direccion;


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
     * @ORM\Column(type="integer", nullable=false, options={"default": 0})
     */
    private int $orden = 0;

    public function __construct()
    {
        $this->fechaActivacion = new \DateTime();
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

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of activo
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set the value of activo
     *
     * @return  self
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get the value of fechaActivacion
     */
    public function getFechaActivacion(): ?\DateTimeInterface
    {
        return $this->fechaActivacion;
    }

    /**
     * Set the value of fechaActivacion
     *
     * @return  self
     */
    public function setFechaActivacion(\DateTimeInterface $fechaActivacion): self
    {
        $this->fechaActivacion = $fechaActivacion;

        return $this;
    }

    /**
     * Get the value of tipoEstructura
     */
    public function getTipoEstructura()
    {
        return $this->tipoEstructura;
    }

    /**
     * Set the value of tipoEstructura
     *
     * @return  self
     */
    public function setTipoEstructura($tipoEstructura)
    {
        $this->tipoEstructura = $tipoEstructura;

        return $this;
    }

    /**
     * Get the value of estructura
     */
    public function getEstructura()
    {
        return $this->estructura;
    }

    /**
     * Set the value of estructura
     *
     * @return  self
     */
    public function setEstructura($estructura)
    {
        $this->estructura = $estructura;

        return $this;
    }

    /**
     * Get the value of categoriaEstructura
     */
    public function getCategoriaEstructura()
    {
        return $this->categoriaEstructura;
    }

    /**
     * Set the value of categoriaEstructura
     *
     * @return  self
     */
    public function setCategoriaEstructura($categoriaEstructura)
    {
        $this->categoriaEstructura = $categoriaEstructura;

        return $this;
    }

    /**
     * Get the value of nivel
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set the value of nivel
     *
     * @return  self
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }


    /**
     * Get the value of ubicacion
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;

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
     * @return bool|null
     */
    public function getEsEntidad(): ?bool
    {
        return $this->esEntidad;
    }

    /**
     * @param bool|null $esEntidad
     */
    public function setEsEntidad(?bool $esEntidad): void
    {
        $this->esEntidad = $esEntidad;
    }

    /**
     * @return bool|null
     */
    public function getCentroAutorizadoPosgrado(): ?bool
    {
        return $this->centroAutorizadoPosgrado;
    }

    /**
     * @param bool|null $centroAutorizadoPosgrado
     */
    public function setCentroAutorizadoPosgrado(?bool $centroAutorizadoPosgrado): void
    {
        $this->centroAutorizadoPosgrado = $centroAutorizadoPosgrado;
    }

    public function __toString(): string
    {
        return $this->getNombre() ?? ''; // Asume que hay un campo 'nombre' o usa el campo apropiado
    }

    /**
     * @return int
     */
    public function getOrden(): int
    {
        return $this->orden;
    }

    /**
     * @param int $orden
     */
    public function setOrden(int $orden): void
    {
        $this->orden = $orden;
    }

}
