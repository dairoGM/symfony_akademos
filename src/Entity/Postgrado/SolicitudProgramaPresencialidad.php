<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseEntity;
use App\Entity\Institucion\Institucion;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbr_solicitud_programa_presencialidad")
 */
class SolicitudProgramaPresencialidad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="PresencialidadPrograma")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?PresencialidadPrograma $presencialidadPrograma;

    /**
     * @ORM\ManyToOne(targetEntity="SolicitudPrograma")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?SolicitudPrograma $solicitudPrograma = null;

    /**
     * @return PresencialidadPrograma|null
     */
    public function getPresencialidadPrograma(): ?PresencialidadPrograma
    {
        return $this->presencialidadPrograma;
    }

    /**
     * @param PresencialidadPrograma|null $presencialidadPrograma
     */
    public function setPresencialidadPrograma(?PresencialidadPrograma $presencialidadPrograma): void
    {
        $this->presencialidadPrograma = $presencialidadPrograma;
    }

    /**
     * @return SolicitudPrograma|null
     */
    public function getSolicitudPrograma(): ?SolicitudPrograma
    {
        return $this->solicitudPrograma;
    }

    /**
     * @param SolicitudPrograma|null $solicitudPrograma
     */
    public function setSolicitudPrograma(?SolicitudPrograma $solicitudPrograma): void
    {
        $this->solicitudPrograma = $solicitudPrograma;
    }


}
