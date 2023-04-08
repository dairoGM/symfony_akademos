<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseEntity;
use App\Entity\Institucion\Institucion;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbr_programa_academico_reabierto_institucion")
 */
class ProgramaAcademicoReabiertoInstitucion extends BaseEntity
{


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pregrado\ProgramaAcademicoReabierto")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?ProgramaAcademicoReabierto $programaAcademicoReabierto = null;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institucion\Institucion")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?Institucion $institucion;

    /**
     * @return ProgramaAcademicoReabierto|null
     */
    public function getProgramaAcademicoReabierto(): ?ProgramaAcademicoReabierto
    {
        return $this->programaAcademicoReabierto;
    }

    /**
     * @param ProgramaAcademicoReabierto|null $programaAcademicoReabierto
     */
    public function setProgramaAcademicoReabierto(?ProgramaAcademicoReabierto $programaAcademicoReabierto): void
    {
        $this->programaAcademicoReabierto = $programaAcademicoReabierto;
    }

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



}
