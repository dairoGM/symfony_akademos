<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseEntity;
use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbr_historico_estado_programa_academico")
 */
class HistoricoEstadoProgramaAcademico extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\SolicitudProgramaAcademico")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?SolicitudProgramaAcademico $solicitudProgramaAcademico = null;

    /**
     * @ORM\ManyToOne(targetEntity="EstadoProgramaAcademico")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?EstadoProgramaAcademico $estadoProgramaAcademico;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\CursoAcademico")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?CursoAcademico $cursoAcademico;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamenAprobacion = null;

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

    /**
     * @return EstadoProgramaAcademico|null
     */
    public function getEstadoProgramaAcademico(): ?EstadoProgramaAcademico
    {
        return $this->estadoProgramaAcademico;
    }

    /**
     * @param EstadoProgramaAcademico|null $estadoProgramaAcademico
     */
    public function setEstadoProgramaAcademico(?EstadoProgramaAcademico $estadoProgramaAcademico): void
    {
        $this->estadoProgramaAcademico = $estadoProgramaAcademico;
    }

    /**
     * @return CursoAcademico|null
     */
    public function getCursoAcademico(): ?CursoAcademico
    {
        return $this->cursoAcademico;
    }

    /**
     * @param CursoAcademico|null $cursoAcademico
     */
    public function setCursoAcademico(?CursoAcademico $cursoAcademico): void
    {
        $this->cursoAcademico = $cursoAcademico;
    }

    /**
     * @return string|null
     */
    public function getDictamenAprobacion(): ?string
    {
        return $this->dictamenAprobacion;
    }

    /**
     * @param string|null $dictamenAprobacion
     */
    public function setDictamenAprobacion(?string $dictamenAprobacion): void
    {
        $this->dictamenAprobacion = $dictamenAprobacion;
    }


}
