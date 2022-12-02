<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbr_solicitud_programa_votacion")
 */
class SolicitudProgramaVotacion extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="MiembrosCopep")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?MiembrosCopep $miembrosCopep = null;

    /**
     * @ORM\ManyToOne(targetEntity="SolicitudPrograma")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?SolicitudPrograma $solicitudPrograma = null;


    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private ?bool $voto;

    /**
     * @return MiembrosCopep|null
     */
    public function getMiembrosCopep(): ?MiembrosCopep
    {
        return $this->miembrosCopep;
    }

    /**
     * @param MiembrosCopep|null $miembrosCopep
     */
    public function setMiembrosCopep(?MiembrosCopep $miembrosCopep): void
    {
        $this->miembrosCopep = $miembrosCopep;
    }

    /**
     * @return bool|null
     */
    public function getVoto(): ?bool
    {
        return $this->voto;
    }

    /**
     * @param bool|null $voto
     */
    public function setVoto(?bool $voto): void
    {
        $this->voto = $voto;
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
