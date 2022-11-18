<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_editorial")
 */
class InstitucionEditorial extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Institucion $institucion;


    /**
     * @ORM\Column(type="string", nullable=false, length="100")
     */
    private ?string $nombreEditorial = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $direccionElectronicaEditorial = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $descripcionEditorial = null;


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
    public function getNombreEditorial(): ?string
    {
        return $this->nombreEditorial;
    }

    /**
     * @param string|null $nombreEditorial
     */
    public function setNombreEditorial(?string $nombreEditorial): void
    {
        $this->nombreEditorial = $nombreEditorial;
    }

    /**
     * @return string|null
     */
    public function getDireccionElectronicaEditorial(): ?string
    {
        return $this->direccionElectronicaEditorial;
    }

    /**
     * @param string|null $direccionElectronicaEditorial
     */
    public function setDireccionElectronicaEditorial(?string $direccionElectronicaEditorial): void
    {
        $this->direccionElectronicaEditorial = $direccionElectronicaEditorial;
    }

    /**
     * @return string|null
     */
    public function getDescripcionEditorial(): ?string
    {
        return $this->descripcionEditorial;
    }

    /**
     * @param string|null $descripcionEditorial
     */
    public function setDescripcionEditorial(?string $descripcionEditorial): void
    {
        $this->descripcionEditorial = $descripcionEditorial;
    }


}
