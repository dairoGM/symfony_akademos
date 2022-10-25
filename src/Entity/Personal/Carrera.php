<?php

namespace App\Entity\Personal;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="personal.tbn_carrera")
 */
class Carrera extends BaseNomenclator
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personal\NivelEscolar")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?NivelEscolar $nivelEscolar;

    public function getNivelEscolar()
    {
        return $this->nivelEscolar;
    }

    public function setNivelEscolar($nivelEscolar)
    {
        $this->nivelEscolar = $nivelEscolar;

        return $this;
    }
}
