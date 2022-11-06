<?php

namespace App\Entity\Institucion;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="institucion.tbn_nivel_acreditacion")
 */
class NivelAcreditacion extends BaseNomenclator
{
    /**
     * @ORM\Column(type="integer", nullable=true, length="10")
     */
    private ?string $duracion = null;


    public function getDuracion()
    {
        return $this->duracion;
    }

    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;

        return $this;
    }
}
