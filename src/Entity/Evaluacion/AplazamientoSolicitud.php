<?php

namespace App\Entity\Evaluacion;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use App\Entity\Evaluacion\Solicitud;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="evaluacion.tbd_aplazamiento_solicitud")
 */
class AplazamientoSolicitud extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="Solicitud")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Solicitud $solicitud;


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $fechaPropuestaAplazamiento;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $aprobado = true;

    /**
     * @return \App\Entity\Evaluacion\Solicitud|null
     */
    public function getSolicitud(): ?\App\Entity\Evaluacion\Solicitud
    {
        return $this->solicitud;
    }

    /**
     * @param \App\Entity\Evaluacion\Solicitud|null $solicitud
     */
    public function setSolicitud(?\App\Entity\Evaluacion\Solicitud $solicitud): void
    {
        $this->solicitud = $solicitud;
    }

    /**
     * @return mixed
     */
    public function getFechaPropuestaAplazamiento()
    {
        return $this->fechaPropuestaAplazamiento;
    }

    /**
     * @param mixed $fechaPropuestaAplazamiento
     */
    public function setFechaPropuestaAplazamiento($fechaPropuestaAplazamiento): void
    {
        $this->fechaPropuestaAplazamiento = $fechaPropuestaAplazamiento;
    }

    /**
     * @return bool|null
     */
    public function getAprobado(): ?bool
    {
        return $this->aprobado;
    }

    /**
     * @param bool|null $aprobado
     */
    public function setAprobado(?bool $aprobado): void
    {
        $this->aprobado = $aprobado;
    }


}
