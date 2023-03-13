<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseEntity;
use App\Entity\Postgrado\Comision;
use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbr_solicitud_programa_academico_plan_estudio")
 */
class SolicitudProgramaAcademicoPlanEstudio extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\PlanEstudio")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?PlanEstudio $planEstudio = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\SolicitudProgramaAcademico")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?SolicitudProgramaAcademico $solicitudProgramaAcademico = null;

    /**
     * @return PlanEstudio|null
     */
    public function getPlanEstudio(): ?PlanEstudio
    {
        return $this->planEstudio;
    }

    /**
     * @param PlanEstudio|null $planEstudio
     */
    public function setPlanEstudio(?PlanEstudio $planEstudio): void
    {
        $this->planEstudio = $planEstudio;
    }

    /**
     * @return SolicitudProgramaAcademico|null
     */
    public function getSolicitudProgramaAcademico(): ?SolicitudProgramaAcademico
    {
        return $this->solicitudProgramaAcademico;
    }

    /**
     * @param SolicitudProgramaAcademico|null $solicitudProgramaAcademico
     */
    public function setSolicitudProgramaAcademico(?SolicitudProgramaAcademico $solicitudProgramaAcademico): void
    {
        $this->solicitudProgramaAcademico = $solicitudProgramaAcademico;
    }


}
