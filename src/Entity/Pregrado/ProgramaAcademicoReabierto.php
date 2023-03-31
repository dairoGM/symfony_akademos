<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbd_programa_academico_reabierto")
 */
class ProgramaAcademicoReabierto extends BaseEntity
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
    private ?string $fundamentacion = null;


    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $dictamenDgp = null;

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
    public function getFundamentacion(): ?string
    {
        return $this->fundamentacion;
    }

    /**
     * @param string|null $fundamentacion
     */
    public function setFundamentacion(?string $fundamentacion): void
    {
        $this->fundamentacion = $fundamentacion;
    }

    /**
     * @return string|null
     */
    public function getDictamenDgp(): ?string
    {
        return $this->dictamenDgp;
    }

    /**
     * @param string|null $dictamenDgp
     */
    public function setDictamenDgp(?string $dictamenDgp): void
    {
        $this->dictamenDgp = $dictamenDgp;
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


}
