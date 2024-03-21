<?php

namespace App\Entity\Configuracion;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="configuracion.tbn_idioma")
 */
class Idioma extends BaseNomenclator
{
    
    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private ?string $siglas;

    /**
     * @return string|null
     */
    public function getSiglas(): ?string
    {
        return $this->siglas;
    }

    /**
     * @param string|null $siglas
     */
    public function setSiglas($siglas): void
    {
        $this->siglas = $siglas;
    }


}
