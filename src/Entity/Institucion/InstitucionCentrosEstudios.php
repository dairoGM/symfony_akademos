<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_centros_estudios")
 */
class InstitucionCentrosEstudios extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Institucion $institucion;


    /**
     * @ORM\Column(type="string", nullable=false, length="100")
     */
    private ?string $nombreCentroEstudio = null;


    /**
     * @ORM\Column(type="string", nullable=false, length="255")
     */
    private ?string $descripcionCentroEstudio= null;


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
    public function getNombreCentroEstudio(): ?string
    {
        return $this->nombreCentroEstudio;
    }

    /**
     * @param string|null $nombreCentroEstudio
     */
    public function setNombreCentroEstudio(?string $nombreCentroEstudio): void
    {
        $this->nombreCentroEstudio = $nombreCentroEstudio;
    }

    /**
     * @return string|null
     */
    public function getDescripcionCentroEstudio(): ?string
    {
        return $this->descripcionCentroEstudio;
    }

    /**
     * @param string|null $descripcionCentroEstudio
     */
    public function setDescripcionCentroEstudio(?string $descripcionCentroEstudio): void
    {
        $this->descripcionCentroEstudio = $descripcionCentroEstudio;
    }


}
