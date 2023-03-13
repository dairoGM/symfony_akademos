<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseEntity;
use App\Entity\Postgrado\Comision;
use App\Entity\Postgrado\SolicitudPrograma;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbr_solicitud_programa_academico_comision_nacional")
 */
class SolicitudProgramaAcademicoComisionNacional extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\ComisionNacional")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?ComisionNacional $comisionNacional = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\SolicitudProgramaAcademico")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?SolicitudProgramaAcademico $solicitudProgramaAcademico = null;

    /**
     * @return ComisionNacional|null
     */
    public function getComisionNacional(): ?ComisionNacional
    {
        return $this->comisionNacional;
    }

    /**
     * @param ComisionNacional|null $comisionNacional
     */
    public function setComisionNacional(?ComisionNacional $comisionNacional): void
    {
        $this->comisionNacional = $comisionNacional;
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
