<?php

namespace App\Entity\Evaluacion;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="evaluacion.tbd_comision")
 */
class Comision extends BaseNomenclator
{
    /**
     * @ORM\ManyToOne(targetEntity="Solicitud")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE"))
     */
    private ?Solicitud $solicitud;

    /**
     * @return Solicitud|null
     */
    public function getSolicitud(): ?Solicitud
    {
        return $this->solicitud;
    }

    /**
     * @param Solicitud|null $solicitud
     */
    public function setSolicitud(?Solicitud $solicitud): void
    {
        $this->solicitud = $solicitud;
    }


}
