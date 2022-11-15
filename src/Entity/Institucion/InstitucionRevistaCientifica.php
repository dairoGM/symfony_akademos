<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_revista_cientifica")
 */
class InstitucionRevistaCientifica extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucion;


    /**
     * @ORM\ManyToOne(targetEntity="Visibilidad")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Visibilidad $visibilidad;


    /**
     * @ORM\Column(type="string", nullable=false, length="100")
     */
    private ?string $nombreRevista = null;

    /**
     * @ORM\Column(type="string", nullable=false, length="100")
     */
    private ?string $indexadaEn = null;

    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $direccionElectronicaRevista = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $descripcionRevista = null;

    /**
     * @return string|null
     */
    public function getdireccionElectronicaRevista(): ?string
    {
        return $this->direccionElectronicaRevista;
    }

    /**
     * @param string|null $direccionElectronicaRevista
     */
    public function setdireccionElectronicaRevista(?string $direccionElectronicaRevista): void
    {
        $this->direccionElectronicaRevista = $direccionElectronicaRevista;
    }

    /**
     * @return string|null
     */
    public function getdescripcionRevista(): ?string
    {
        return $this->descripcionRevista;
    }

    /**
     * @param string|null $descripcionRevista
     */
    public function setdescripcionRevista(?string $descripcionRevista): void
    {
        $this->descripcionRevista = $descripcionRevista;
    }


    /**
     * @return Institucion|null
     */
    public function getInstitucion(): ?Institucion
    {
        return $this->institucion;
    }

    /**
     * @param Institucion|null $institucion
     */
    public function setInstitucion(?Institucion $institucion): void
    {
        $this->institucion = $institucion;
    }

    /**
     * @return string|null
     */
    public function getNombreRevista(): ?string
    {
        return $this->nombreRevista;
    }

    /**
     * @param string|null $nombreRevista
     */
    public function setNombreRevista(?string $nombreRevista): void
    {
        $this->nombreRevista = $nombreRevista;
    }

    /**
     * @return string|null
     */
    public function getIndexadaEn(): ?string
    {
        return $this->indexadaEn;
    }

    /**
     * @param string|null $indexadaEn
     */
    public function setIndexadaEn(?string $indexadaEn): void
    {
        $this->indexadaEn = $indexadaEn;
    }


    /**
     * @return Institucion|null
     */
    public function getVisibilidad(): ?Visibilidad
    {
        return $this->visibilidad;
    }

    /**
     * @param Institucion|null $visibilidad
     */
    public function setVisibilidad(?Visibilidad $visibilidad): void
    {
        $this->visibilidad = $visibilidad;
    }


}
