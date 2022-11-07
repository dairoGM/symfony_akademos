<?php

namespace App\Entity\Institucion;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbr_institucion_afiliacion")
 */
class InstitucionAfiliacion extends BaseEntity
{

    /**
     * @ORM\Column(type="string", nullable=false, length="10")
     */
    private ?string $afiliacion = null;

    /**
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Institucion $institucion;

    /**
     * @return string|null
     */
    public function getAfiliacion(): ?string
    {
        return $this->afiliacion;
    }

    /**
     * @param string|null $afiliacion
     */
    public function setAfiliacion(?string $afiliacion): void
    {
        $this->afiliacion = $afiliacion;
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


}
