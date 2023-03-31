<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbd_programa_academico_desactivado")
 */
class ProgramaAcademicoDesactivado extends BaseEntity
{


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\SolicitudProgramaAcademico")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?SolicitudProgramaAcademico $solicitudProgramaAcademico = null;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\CursoAcademico")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?CursoAcademico $cursoAcademico;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $solicitudCentroRector = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamenAprobacion = null;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $resolucion = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Regex(
     *           pattern= "/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/",
     *           match=   true,
     *           message= "Caracteres no válidos, por favor verifique."
     * )
     */
    private ?string $descripcion = null;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $fechaEliminacion;

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
    public function getSolicitudCentroRector(): ?string
    {
        return $this->solicitudCentroRector;
    }

    /**
     * @param string|null $solicitudCentroRector
     */
    public function setSolicitudCentroRector(?string $solicitudCentroRector): void
    {
        $this->solicitudCentroRector = $solicitudCentroRector;
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

    /**
     * @return string|null
     */
    public function getResolucion(): ?string
    {
        return $this->resolucion;
    }

    /**
     * @param string|null $resolucion
     */
    public function setResolucion(?string $resolucion): void
    {
        $this->resolucion = $resolucion;
    }

    /**
     * @return string|null
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param string|null $descripcion
     */
    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getFechaEliminacion()
    {
        return $this->fechaEliminacion;
    }

    /**
     * @param mixed $fechaEliminacion
     */
    public function setFechaEliminacion($fechaEliminacion): void
    {
        $this->fechaEliminacion = $fechaEliminacion;
    }


}
