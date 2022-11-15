<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_sedes")
 */
class InstitucionSedes extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucion;


    /**
     * @ORM\Column(type="string", nullable=false, length="100")
     */
    private ?string $nombreSede = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $direccionSede = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="100")
     */
    private ?string $coordenadasSede = null;

    /**
     * @return string|null
     */
    public function getDireccionSede(): ?string
    {
        return $this->direccionSede;
    }

    /**
     * @param string|null $direccionSede
     */
    public function setDireccionSede(?string $direccionSede): void
    {
        $this->direccionSede = $direccionSede;
    }

    /**
     * @return string|null
     */
    public function getCoordenadasSede(): ?string
    {
        return $this->coordenadasSede;
    }

    /**
     * @param string|null $coordenadasSede
     */
    public function setCoordenadasSede(?string $coordenadasSede): void
    {
        $this->coordenadasSede = $coordenadasSede;
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
    public function getNombreSede(): ?string
    {
        return $this->nombreSede;
    }

    /**
     * @param string|null $nombreSede
     */
    public function setNombreSede(?string $nombreSede): void
    {
        $this->nombreSede = $nombreSede;
    }

}
