<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbr_tipo_solicitud_clasificacion")
 */
class TipoSolicitudClasificacion extends BaseEntity
{


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Postgrado\TipoSolicitud")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private ?TipoSolicitud $tipoSolicitud;

    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $clasificacion = null;

    /**
     * @return TipoSolicitud|null
     */
    public function getTipoSolicitud(): ?TipoSolicitud
    {
        return $this->tipoSolicitud;
    }

    /**
     * @param TipoSolicitud|null $tipoSolicitud
     */
    public function setTipoSolicitud(?TipoSolicitud $tipoSolicitud): void
    {
        $this->tipoSolicitud = $tipoSolicitud;
    }

    /**
     * @return string|null
     */
    public function getClasificacion(): ?string
    {
        return $this->clasificacion;
    }

    /**
     * @param string|null $clasificacion
     */
    public function setClasificacion(?string $clasificacion): void
    {
        $this->clasificacion = $clasificacion;
    }


}
