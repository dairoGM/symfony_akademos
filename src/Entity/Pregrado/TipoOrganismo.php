<?php

namespace App\Entity\Pregrado;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pregrado.tbn_tipo_organismo")
 */
class TipoOrganismo extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=false, length="5")
     */
    private ?string $siglas = null;

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
    public function setSiglas(?string $siglas): void
    {
        $this->siglas = $siglas;
    }

}
