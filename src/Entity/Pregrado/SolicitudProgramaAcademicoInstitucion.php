<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseEntity;
use App\Entity\Institucion\CategoriaAcreditacion;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\CategoriaAcreditacion")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CategoriaAcreditacion $categoriaAcreditacion;

    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?int $duracionCursoDiurno = null;


    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?int $duracionCursoPorEncuentro = null;
    /**
     * @ORM\Column(type="integer", nullable=true, length="255")
     */
    private ?int $duracionCursoADistancia = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $modalidadDiurno = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $modalidadPorEncuentro = false;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $modalidadADistancia = false;

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

    /**
     * @return CategoriaAcreditacion|null
     */
    public function getCategoriaAcreditacion(): ?CategoriaAcreditacion
    {
        return $this->categoriaAcreditacion;
    }

    /**
     * @param CategoriaAcreditacion|null $categoriaAcreditacion
     */
    public function setCategoriaAcreditacion(?CategoriaAcreditacion $categoriaAcreditacion): void
    {
        $this->categoriaAcreditacion = $categoriaAcreditacion;
    }

    /**
     * @return int|null
     */
    public function getDuracionCursoDiurno(): ?int
    {
        return $this->duracionCursoDiurno;
    }

    /**
     * @param int|null $duracionCursoDiurno
     */
    public function setDuracionCursoDiurno(?int $duracionCursoDiurno): void
    {
        $this->duracionCursoDiurno = $duracionCursoDiurno;
    }

    /**
     * @return int|null
     */
    public function getDuracionCursoPorEncuentro(): ?int
    {
        return $this->duracionCursoPorEncuentro;
    }

    /**
     * @param int|null $duracionCursoPorEncuentro
     */
    public function setDuracionCursoPorEncuentro(?int $duracionCursoPorEncuentro): void
    {
        $this->duracionCursoPorEncuentro = $duracionCursoPorEncuentro;
    }

    /**
     * @return int|null
     */
    public function getDuracionCursoADistancia(): ?int
    {
        return $this->duracionCursoADistancia;
    }

    /**
     * @param int|null $duracionCursoADistancia
     */
    public function setDuracionCursoADistancia(?int $duracionCursoADistancia): void
    {
        $this->duracionCursoADistancia = $duracionCursoADistancia;
    }

    /**
     * @return bool|null
     */
    public function getModalidadDiurno(): ?bool
    {
        return $this->modalidadDiurno;
    }

    /**
     * @param bool|null $modalidadDiurno
     */
    public function setModalidadDiurno(?bool $modalidadDiurno): void
    {
        $this->modalidadDiurno = $modalidadDiurno;
    }

    /**
     * @return bool|null
     */
    public function getModalidadPorEncuentro(): ?bool
    {
        return $this->modalidadPorEncuentro;
    }

    /**
     * @param bool|null $modalidadPorEncuentro
     */
    public function setModalidadPorEncuentro(?bool $modalidadPorEncuentro): void
    {
        $this->modalidadPorEncuentro = $modalidadPorEncuentro;
    }

    /**
     * @return bool|null
     */
    public function getModalidadADistancia(): ?bool
    {
        return $this->modalidadADistancia;
    }

    /**
     * @param bool|null $modalidadADistancia
     */
    public function setModalidadADistancia(?bool $modalidadADistancia): void
    {
        $this->modalidadADistancia = $modalidadADistancia;
    }


}
