<?php

namespace App\Entity\Tramite;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tramite.tbn_tipo_pasaporte")
 */
class TipoPasaporte extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=false, length="10")
     */
    private ?string $siglas=null;


    public function getSiglas()
    {
        return $this->siglas;
    }

    public function setSiglas($siglas)
    {
        $this->siglas = $siglas;

        return $this;
    }
}
