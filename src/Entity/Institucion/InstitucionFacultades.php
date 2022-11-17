<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_facultades")
 */
class InstitucionFacultades extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucion;


    /**
     * @ORM\Column(type="string", nullable=false, length="100")
     */
    private ?string $nombreFacultad = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $descripcionFacultad = null;


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
    public function getNombreFacultad(): ?string
    {
        return $this->nombreFacultad;
    }

    /**
     * @param string|null $nombreFacultad
     */
    public function setNombreFacultad(?string $nombreFacultad): void
    {
        $this->nombreFacultad = $nombreFacultad;
    }

    /**
     * @return string|null
     */
    public function getDescripcionFacultad(): ?string
    {
        return $this->descripcionFacultad;
    }

    /**
     * @param string|null $descripcionFacultad
     */
    public function setDescripcionFacultad(?string $descripcionFacultad): void
    {
        $this->descripcionFacultad = $descripcionFacultad;
    }

}
