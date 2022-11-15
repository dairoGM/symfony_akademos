<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_membresia")
 */
class InstitucionMembresia extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucion;


    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?string $idMembresia = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $nombreMembresia = null;


    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?string $idTipoMembresia = null;

    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $nombreTipoMembresia = null;


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
    public function getIdMembresia(): ?string
    {
        return $this->idMembresia;
    }

    /**
     * @param string|null $idMembresia
     */
    public function setIdMembresia(?string $idMembresia): void
    {
        $this->idMembresia = $idMembresia;
    }

    /**
     * @return string|null
     */
    public function getNombreMembresia(): ?string
    {
        return $this->nombreMembresia;
    }

    /**
     * @param string|null $nombreMembresia
     */
    public function setNombreMembresia(?string $nombreMembresia): void
    {
        $this->nombreMembresia = $nombreMembresia;
    }

    /**
     * @return string|null
     */
    public function getIdTipoMembresia(): ?string
    {
        return $this->idTipoMembresia;
    }

    /**
     * @param string|null $idTipoMembresia
     */
    public function setIdTipoMembresia(?string $idTipoMembresia): void
    {
        $this->idTipoMembresia = $idTipoMembresia;
    }

    /**
     * @return string|null
     */
    public function getNombreTipoMembresia(): ?string
    {
        return $this->nombreTipoMembresia;
    }

    /**
     * @param string|null $nombreTipoMembresia
     */
    public function setNombreTipoMembresia(?string $nombreTipoMembresia): void
    {
        $this->nombreTipoMembresia = $nombreTipoMembresia;
    }





}
