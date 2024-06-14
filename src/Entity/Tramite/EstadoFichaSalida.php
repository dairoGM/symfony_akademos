<?php

namespace App\Entity\Tramite;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tramite.tbn_estado_ficha_salida")
 */
class EstadoFichaSalida extends BaseNomenclator
{


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $documentoSalida = true;

    /**
     * @return bool|null
     */
    public function getDocumentoSalida(): ?bool
    {
        return $this->documentoSalida;
    }

    /**
     * @param bool|null $documentoSalida
     */
    public function setDocumentoSalida(?bool $documentoSalida): void
    {
        $this->documentoSalida = $documentoSalida;
    }


}
