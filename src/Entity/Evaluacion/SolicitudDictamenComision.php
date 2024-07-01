<?php

namespace App\Entity\Evaluacion;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use App\Entity\Evaluacion\EstadoSolicitud;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="evaluacion.tbr_solicitud_dictamen_comision")
 */
class SolicitudDictamenComision extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Solicitud")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Solicitud $solicitud;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamenComision = null;

    /**
     * @return Solicitud|null
     */
    public function getSolicitud(): ?Solicitud
    {
        return $this->solicitud;
    }

    /**
     * @param Solicitud|null $solicitud
     */
    public function setSolicitud(?Solicitud $solicitud): void
    {
        $this->solicitud = $solicitud;
    }

    /**
     * @return string|null
     */
    public function getDictamenComision(): ?string
    {
        return $this->dictamenComision;
    }

    /**
     * @param string|null $dictamenComision
     */
    public function setDictamenComision(?string $dictamenComision): void
    {
        $this->dictamenComision = $dictamenComision;
    }


}
