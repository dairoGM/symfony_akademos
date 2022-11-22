<?php

namespace App\Entity\Postgrado;

use App\Entity\BaseNomenclator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="postgrado.tbn_tipo_programa")
 */
class TipoPrograma extends BaseNomenclator
{
    /**
     * @ORM\Column(type="string", nullable=true, length="255")
     */
    private ?string $codigo = null;

    /**
     * @return string|null
     */
    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    /**
     * @param string|null $codigo
     */
    public function setCodigo(?string $codigo): void
    {
        $this->codigo = $codigo;
    }


}
