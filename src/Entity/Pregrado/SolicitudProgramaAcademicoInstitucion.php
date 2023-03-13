<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseEntity;
use App\Entity\Postgrado\Comision;
use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Institucion\Institucion;
/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbr_solicitud_programa_academico_institucion")
 */
class SolicitudProgramaAcademicoInstitucion extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Institucion $institucion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\SolicitudProgramaAcademico")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?SolicitudProgramaAcademico $solicitudProgramaAcademico;

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
