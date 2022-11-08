<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbr_solicitud_programa_comision")
 */
class SolicitudProgramaComision extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="Comision")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Comision $comision = null;

    /**
     * @ORM\ManyToOne(targetEntity="SolicitudPrograma")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?SolicitudPrograma $solicitudPrograma = null;


    public function getComision()
    {
        return $this->comision;
    }

    public function setComision($comision)
    {
        $this->comision = $comision;

        return $this;
    }


    public function getSolicitudPrograma()
    {
        return $this->solicitudPrograma;
    }

    public function setSolicitudPrograma($solicitudPrograma)
    {
        $this->solicitudPrograma = $solicitudPrograma;

        return $this;
    }


}
